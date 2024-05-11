<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Foundation\Readers;

use Illuminate\Support\Arr;

class JsonReader extends Reader
{
    /**
     * file name variable
     *
     * @var string
     */
    public string $file_name = 'swagger.json';

    /**
     * read document content
     *
     * @return array
     */
    public function read(): array
    {
        if ($this->file_exists == false) {
            return [];
        }

        return Arr::dot($this->swaggerdoc->filesystem->json($this->file_path));
    }

    /**
     * write function
     *
     * @return void
     */
    public function write(): void
    {
        $this->swaggerdoc->filesystem->put(
            path: $this->file_path,
            contents: json_encode(Arr::undot($this->content), JSON_PRETTY_PRINT));
    }
}