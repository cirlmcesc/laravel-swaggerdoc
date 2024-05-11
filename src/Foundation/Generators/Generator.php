<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Foundation\Generators;

use Cirlmcesc\LaravelSwaggerdoc\LaravelSwaggerdoc;

abstract class Generator
{
    /**
     * swaggerdoc variable
     *
     * @var LaravelSwaggerdoc
     */
    protected LaravelSwaggerdoc $swaggerdoc;

    /**
     * documentation_path variable
     *
     * @var string|null
     */
    protected string $documentations_path;

    /**
     * focus file variable json or yaml
     *
     * @var string
     */
    protected string $focus_file = 'json';

    /**
     * rewrite variable
     *
     * @var bool
     */
    protected bool $rewrite = true;

    /**
     * upgrade mode variable
     *
     * @var string default revise options: main, minor , revise
     */
    protected string $upgrade_mode = 'revise';

    /**
     * construct function
     */
    public function __construct(
        LaravelSwaggerdoc $swaggerdoc,
        string $focus_file = 'json',
        bool $rewrite = true,
        string $upgrade_mode = 'revise'
    ) {
        $this->swaggerdoc = $swaggerdoc;
        $this->documentations_path = $swaggerdoc->getDocumentationPath();
        $this->focus_file = $focus_file;
        $this->rewrite = $rewrite;
        $this->upgrade_mode = $upgrade_mode;
    }

    /**
     * Generate the OpenAPI 3.0 data.
     *
     * @return array
     */
    abstract public function generate(): array;
}