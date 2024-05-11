<?php

namespace Cirlmcesc\LaravelSwaggerdoc;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Filesystem\FilesystemAdapter;
use Cirlmcesc\LaravelSwaggerdoc\Foundation\Generators\Generator;
use Cirlmcesc\LaravelSwaggerdoc\Foundation\Readers\Reader;
use Cirlmcesc\LaravelSwaggerdoc\Foundation\Attributes\Attributer;

class LaravelSwaggerdoc
{
    /**
     * generator relationships 
     * 
     * @var array<string, Generator>
     */
    const array GENERATOR_RELATIONSHIPS = [
        'info' => \Cirlmcesc\LaravelSwaggerdoc\Foundation\Generators\InfoGenerator::class,
        'components' => \Cirlmcesc\LaravelSwaggerdoc\Foundation\Generators\ComponentsGenerator::class,
        'servers' => \Cirlmcesc\LaravelSwaggerdoc\Foundation\Generators\ServersGenerator::class,
        'paths' => \Cirlmcesc\LaravelSwaggerdoc\Foundation\Generators\PathsGenerator::class,
        'tags' => \Cirlmcesc\LaravelSwaggerdoc\Foundation\Generators\TagsGenerator::class,
    
    ];

    /**
     * reader relationships 
     * 
     * @var array<string, Reader>
     */
    const array READER_RELATIONSHIPS = [
        'json' => \Cirlmcesc\LaravelSwaggerdoc\Foundation\Readers\JsonReader::class,
        'yaml' => \Cirlmcesc\LaravelSwaggerdoc\Foundation\Readers\YamlReader::class,
    ];

    /**
     * attributer relationships
     * 
     * @var array<string, Attributer>
     */
    const array ATTRIBUTES_RELATIONSHIPS = [
        'model' => \Cirlmcesc\LaravelSwaggerdoc\Foundation\Attributes\ModelAttributer::class,
        'request' => \Cirlmcesc\LaravelSwaggerdoc\Foundation\Attributes\RequestAttributer::class,
        'test' => \Cirlmcesc\LaravelSwaggerdoc\Foundation\Attributes\TestAttributer::class,
    ];

    /**
     * generator mode focus
     *
     * @var string
     */
    public string $generator_focus_file = 'json';

    /**
     * generator mode rewrite
     *
     * @var boolean
     */
    public bool $generator_rewrite = true;

    /**
     * upgrade mode
     *
     * @var string default revise options: main, minor , revise
     */
    public string $upgrade_mode = 'revise';

    /**
     * previous version
     *
     * @var string
     */
    public string $previous_version;

    /**
     * resovled Generators
     *
     * @var array<string, Generator>
     */
    private array $resovled_generators = [];

    /**
     * resovled readers
     *
     * @var array<string, Reader>
     */
    private array $resovled_readers = [];

    /**
     * resovled attributers
     *
     * @var array<string, Attributer>
     */
    private array $resovled_attributers = [];

    /**
     * filesystem
     *
     * @var FilesystemAdapter
     */
    private FilesystemAdapter $filesystem;

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return match (true) {
            Str::is($key, 'filesystem') => $this->assignFilesystemAdapter(),
            Str::endsWith($key, 'Generator') => $this->assignGenerator($$key),
            Str::endsWith($key, 'Reader') => $this->assignReader($key),
            Str::endsWith($key, 'Attributer') => $this->assignAttributer($key),
            default => $this->$key,
        };
    }

    /**
     * setGeneratorMode
     *
     * @param string $focus
     * @param boolean $rewrite
     * @return self
     */
    public function setGeneratorMode(
        string $focus_file = 'json',
        bool $rewrite = true,
        string $upgrade_mode = 'revise'
    ): self {
        $this->generator_focus_file = $focus_file;
        $this->generator_rewrite = $rewrite;
        $this->upgrade_mode = $upgrade_mode;

        return $this;
    }

    /**
     * get documentation path
     *
     * @return string
     */
    public function getDocumentationPath(): string
    {
        return config('swaggerdoc.file_directory', 'documentations');
    }

    /**
     * get backups path
     *
     * @return string
     */
    public function getBackupPath(): string
    {
        /** @var string config backup directory */
        $config_backup_directory = config('swaggerdoc.backup_directory', 'backups');

        return "{$this->getDocumentationPath()}/$config_backup_directory";
    }

    /**
     * cleanup
     *
     * @return void
     */
    public function cleanup(): void
    {
        $this->resovled_generators = [];
        $this->resovled_readers = [];
        $this->generator_focus_file = 'json';
        $this->generator_rewrite = true;
    }

    /**
     * backup
     *
     * @return self
     */
    public function backup(): self
    {
        if ($this->jsonReader->file_exists || $this->yamlReader->file_exists) {
            $this->previous_version = match ($this->generator_focus_file) {
                'json' => $this->jsonReader->getPreviousVersion(),
                'yaml' => $this->yamlReader->getPreviousVersion(),
                default => config('swaggerdoc.preset_value.info.version', '0.0.1'),
            };

            /** @var string backups folder name */
            $backups_folder_name = value(config(
                    key: 'swaggerdoc.backup_directory_nomenclature',
                    default: fn (string $previous_version): string => Arr::join(
                        array: [
                            now()->format('YmdHis'),
                            $previous_version,
                        ], 
                        glue: '-')),
                $this->previous_version);
            
            /** @var string backups directory */
            $backups_directory = "{$this->getBackupPath()}/$backups_folder_name";

            $this->filesystem->makeDirectory($backups_directory);
            $this->jsonReader->backup();
            $this->yamlReader->backup();
        }

        return $this;
    }

    /**
     * temporary data to reader
     *
     * @param array $data
     * @param string|null $key
     * @return self
     */
    public function temporaryDataToReader(array $data, string $key = null): self
    {
        $this->jsonReader->temporary($data, $key);
        $this->yamlReader->temporary($data, $key);

        return $this;
    }

    /**
     * save
     *
     * @return self
     */
    public function save(): self
    {
        $this->jsonReader->write();
        $this->yamlReader->write();

        return $this;
    }

    /**
     * assign Generator
     *
     * @param string $key
     * @return Generator
     */
    private function assignGenerator(string $key): Generator
    {
        /** @var string target generator class name */
        $target_generator_classname = Str::of($key)
            ->replace('Generator', '')
            ->snake()
            ->__toString();

        if (key_exists($target_generator_classname, $this->resovled_generators)) {
            return $this->resovled_generators[$target_generator_classname];
        }

        /** @var string target generator class */
        $target_generator_class = self::GENERATOR_RELATIONSHIPS[$target_generator_classname];

        $this->resovled_generators[$target_generator_classname] = 
            new $target_generator_class(
                swaggerdoc: $this,
                focus_file: $this->generator_focus_file,
                rewrite: $this->generator_rewrite,
                upgrade_mode: $this->upgrade_mode);

        return $this->resovled_generators[$target_generator_classname];
    }

    /**
     * assign Reader
     *
     * @param string $key
     * @return Reader
     */
    private function assignReader(string $key): Reader
    {
        /** @var string target reader class name */
        $target_reader_classname = Str::of($key)
            ->replace('Reader', '')
            ->snake()
            ->__toString();

        if (key_exists($target_reader_classname, $this->resovled_readers)) {
            return $this->resovled_readers[$target_reader_classname];
        }

        /** @var string target reader class */
        $target_reader_class = self::READER_RELATIONSHIPS[$target_reader_classname];

        $this->resovled_readers[$target_reader_classname] = new $target_reader_class(swaggerdoc: $this);

        return $this->resovled_readers[$target_reader_classname];
    }

    /**
     * assign Attributer
     *
     * @param string $key
     * @return Attributer
     */
    private function assignAttributer(string $key): Attributer
    {
        /** @var string target attrbuter class name */
        $target_attributer_classname = Str::of($key)
            ->replace('Attributer', '')
            ->snake()
            ->__toString();

        if (key_exists($target_attributer_classname, $this->resovled_attributers)) {
            return $this->resovled_attributers[$target_attributer_classname];
        }

        /** @var string target attributer class */
        $target_attributer_class = self::ATTRIBUTES_RELATIONSHIPS[$target_attributer_classname];

        $this->resovled_attributers[$target_attributer_classname] = new $target_attributer_class();

        return $this->resovled_attributers[$target_attributer_classname];
    }

    /**
     * assign filesystem
     *
     * @return FilesystemAdapter
     */
    private function assignFilesystemAdapter(): FilesystemAdapter
    {
        if (empty($this->filesystem)) {
            $this->filesystem = Storage::disk('swaggerdoc');
        }

        return $this->filesystem;
    }
}
