<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Foundation\Readers;

use Illuminate\Support\Arr;

class YamlReader extends Reader
{
    /**
     * file name variable
     *
     * @var string
     */
    public string $file_name = 'swagger.yaml';

    /**
     * read document content
     *
     * @return array
     */
    public function read(): array
    {
        // $this->backup_content = yaml_parse_file($this->file_path);

        return $this->backup_content;
    }

    /**
     * write function
     *
     * @return void
     */
    public function write(): void
    {
        // yaml_emit_file($this->file_path, $this->new_content);
    }
}