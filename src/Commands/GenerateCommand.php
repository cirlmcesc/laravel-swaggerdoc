<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Cirlmcesc\LaravelSwaggerdoc\LaravelSwaggerdoc;

use function Laravel\Prompts\{ progress, spin, info };

class GenerateCommand extends Command
{
    /**
     * generator setps
     * 
     *  1/10:   inspect directory exists
     *  2/10:   initialization generator
     *  3/10:   backups existing file
     *  4/10:   generate new structure
     *  5/10:   generate info
     *  6/10:   generate servers
     *  7/10:   generate components
     *  8/10:   generate paths
     *  9/10:   generate tags
     * 10/10:   fish
     * 
     * @var string[]
     */
    const array SETPS = [
        'inspect directory exists',
        'initialization generator',
        'backup existing file',
        'generate new structure',
        'generate info',
        'generate servers',
        'generate components',
        'generate paths',
        'generate tags',
        'finsh',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swaggerdoc:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * focus file
     *
     * @var string
     */
    private string $focus_file = 'json';

    /**
     * rewrite
     *
     * @var boolean
     */
    private bool $rewrite = true;

    /**
     * upgrade mode
     *
     * @var string default revise options: main, minor , revise
     */
    protected string $upgrade_mode = 'revise';

    /**
     * swaggerdoc
     *
     * @var LaravelSwaggerdoc
     */
    private LaravelSwaggerdoc $swaggerdoc;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(LaravelSwaggerdoc $swaggerdoc)
    {
        $this->swaggerdoc = $swaggerdoc;

        /** @var \Laravel\Prompts\Progress $generator_progress */
        $generator_progress = progress(
            label: __('swaggerdoc::command.generate.progress.label'),
            steps: count(self::SETPS));

        $generator_progress->start();

        foreach(self::SETPS as $step) {
            spin(callback: fn() => sleep(1), message: $step);

            $this->{Str::studly($step)}();

            $generator_progress->advance();
        }

        $generator_progress->finish();

        info(__('swaggerdoc::command.generate.progress.finish'));

        dd(\Illuminate\Support\Arr::undot($this->swaggerdoc->jsonReader->content));
    }

    /**
     * inspect directory exists
     *
     * @return void
     */
    private function inspectDirectoryExists(): void
    {
        /** Confirm if the document directory exists, if not, create it */
        if (! $this->swaggerdoc->filesystem->directoryExists($this->swaggerdoc->getDocumentationPath())) {
            $this->swaggerdoc->filesystem->makeDirectory($this->swaggerdoc->getDocumentationPath());
        }
        
        /** Confirm if the document backups directory exists, if not, create it */
        if (! $this->swaggerdoc->filesystem->directoryExists($this->swaggerdoc->getBackupPath())) {
            $this->swaggerdoc->filesystem->makeDirectory($this->swaggerdoc->getBackupPath());
        }
    }

    /**
     * initialization generator
     *
     * @return void
     */
    private function initializationGenerator(): void
    {
        $this->swaggerdoc->cleanup();

        $this->swaggerdoc->setGeneratorMode(
            focus_file: $this->focus_file,
            rewrite: $this->rewrite,
            upgrade_mode: $this->upgrade_mode);
    }

    /**
     * backup existing file
     *
     * @return void
     */
    private function backupExistingFile(): void
    {
        if ($this->swaggerdoc->jsonReader->file_exists
            || $this->swaggerdoc->yamlReader->file_exists) {
            $this->swaggerdoc->backup();
        }
    }

    /**
     * generate new structure
     *
     * @return void
     */
    private function generateNewStructure(): void
    {
        $this->swaggerdoc->temporaryDataToReader(data: [
            'openapi' => config('swaggerdoc.preset_value.openapi', '3.1.0'),
            'info' => [],
            'servers' => [],
            'components' => [],
            'paths' => [],
            'tags' => [],
        ]);
    }

    /**
     * generate info
     *
     * @return void
     */
    private function generateInfo(): void
    {
        $this->swaggerdoc->temporaryDataToReader(
            data: $this->swaggerdoc->InfoGenerator->generate(),
            key: 'info');
    }

    /**
     * generate servers
     *
     * @return void
     */
    private function generateServers(): void
    {
        $this->swaggerdoc->temporaryDataToReader(
            data: $this->swaggerdoc->ServersGenerator->generate(),
            key: 'servers');
    }

    /**
     * generate components
     *
     * @return void
     */
    private function generateComponents(): void
    {
        $this->swaggerdoc->temporaryDataToReader(
            data: $this->swaggerdoc->ComponentsGenerator->generate(),
            key: 'components');
    }

    /**
     * generate paths
     *
     * @return void
     */
    private function generatePaths(): void
    {
        $this->swaggerdoc->temporaryDataToReader(
            data: $this->swaggerdoc->PathsGenerator->generate(),
            key: 'paths');
    }

    /**
     * generate tags
     *
     * @return void
     */
    private function generateTags(): void
    {
        $this->swaggerdoc->temporaryDataToReader(
            data: $this->swaggerdoc->TagsGenerator->generate(),
            key: 'tags');
    }

    /**
     * finsh
     *
     * @return void
     */
    private function finsh(): void
    {
        $this->swaggerdoc->cleanup();
    }
}