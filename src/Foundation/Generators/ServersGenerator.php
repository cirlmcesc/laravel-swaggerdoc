<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Foundation\Generators;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ServersGenerator extends Generator
{
    /**
     * content variable
     *
     * @var array
     */
    private array $content = [];

    /**
     * generate
     *
     * @return array
     */
    public function generate(): array
    {
        $this->content = config('swaggerdoc.preset_value.servers', []);

        return $this->content;
    }
}