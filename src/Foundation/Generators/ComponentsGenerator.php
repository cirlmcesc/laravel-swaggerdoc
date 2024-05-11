<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Foundation\Generators;

use Illuminate\Support\Str;

class ComponentsGenerator extends Generator
{
    /**
     * setps
     * 
     * @var string[] 
     */
    const array SETPS = [
        'schemas',
        'parameters',
        'responses',
        'security schemes',
        'examples',
        'requestBodies',
        'headers',
        'links',
        'callbacks',
    ];

    /**
     * extra variable
     *
     * @var array<string, string>
     */
    private $extra_content = [];

    /**
     * content
     *
     * @var array<string, string>
     */
    private $content = [];

    /**
     * generate function
     *
     * @return array
     */
    public function generate(): array
    {
        foreach (self::SETPS as $setp) {
            $this->{'generate' . Str::studly($setp)}();
        }

        return $this->content;
    }

    private function generateSchemas(): self
    {
        return $this;
    }

    private function generateParameters(): self
    {
        return $this;
    }

    private function generateResponses(): self
    {
        return $this;
    }

    private function generateSecuritySchemes(): self
    {
        return $this;
    }

    private function generateExamples(): self
    {
        return $this;
    }

    private function generateRequestBodies(): self
    {
        return $this;
    }

    private function generateHeaders(): self
    {
        return $this;
    }

    private function generateLinks(): self
    {
        return $this;
    }

    private function generateCallbacks(): self
    {
        return $this;
    }
}