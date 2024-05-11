<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Foundation\Readers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Cirlmcesc\LaravelSwaggerdoc\LaravelSwaggerdoc;

abstract class Reader
{
    /**
     * file name variable
     *
     * @var string
     */
    public string $file_name;

    /**
     * file_path variable
     *
     * @var string
     */
    public string $file_path;

    /**
     * file_exists variable
     *
     * @var boolean
     */
    public bool $file_exists = false;

    /**
     * backup_content variable
     *
     * @var array<string>
     */
    public array $backup_content = [];

    /**
     * content variable
     *
     * @var array<string>
     */
    public array $content = [];

    /**
     * previous version variable
     *
     * @var string
     */
    public string $previous_version;

    /**
     * swaggerdoc variable
     *
     * @var LaravelSwaggerdoc
     */
    protected LaravelSwaggerdoc $swaggerdoc;

    /**
     * construct function
     */
    public function __construct(LaravelSwaggerdoc $swaggerdoc)
    {
        $this->swaggerdoc = $swaggerdoc;

        $this->setFilePath()->setFileExists();

        if ($this->file_exists) {
            $this->content = $this->read();
        }
    }

    /**
     * set file path function
     *
     * @return self
     */
    protected function setFilePath(): self
    {
        $this->file_path = "{$this->swaggerdoc->getDocumentationPath()}/$this->file_name";

        return $this;
    }

    /**
     * setFileExists function
     *
     * @return self
     */
    protected function setFileExists(): self
    {
        $this->file_exists = $this->swaggerdoc->filesystem->exists($this->file_path);

        return $this;
    }

    /**
     * backup function
     *
     * @param string $to_path
     * @return void
     */
    public function backup(string $to_path): void
    {
        if ($this->file_exists) {
            $this->backup_content = $this->read();
            $this->content = [];
            $this->previous_version = $this->backup_content['info.version'];

            $this->swaggerdoc->filesystem->copy(
                from: $this->file_path,
                to: "$to_path/$this->file_name");
        }
    }

    /**
     * get previous version
     *
     * @return string
     */
    public function getPreviousVersion(): string
    {
        return $this->previous_version ?: config('swaggerdoc.preset_value.info.version'. '0.0.1');
    }

    /**
     * temporary data
     *
     * @param array $data
     * @param string|null $key
     * @return self
     */
    public function temporary(array $data, string $key = null): self
    {
        if (empty($key)) {
            $this->content = $data;
        } else {
            $this->content[$key] = $data;
        }

        $this->content = Arr::dot($this->content);

        return $this;
    }

    /**
     * read document content
     *
     * @param string
     * @return array
     */
    abstract public function read(): array;

    /**
     * write document content
     *
     * @return void
     */
    abstract public function write(): void;
}