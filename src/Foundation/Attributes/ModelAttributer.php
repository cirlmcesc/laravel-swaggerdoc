<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Foundation\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class ModelAttributer extends Attributer
{
    /**
     * construct function
     *
     * @param string $name
     */
    public function __construct(
        public string $name,
    ) {}
}