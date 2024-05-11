<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Foundation\Generators;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class InfoGenerator extends Generator
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
        $this
            ->generateTitle()
            ->generateSummary()
            ->generateDescription()
            ->generateTermsOfService()
            ->generateContact()
            ->generateLicense()
            ->generateVersion();
        
        $this->content = Arr::dot($this->content);

        return $this->content;
    }
    
    /**
     * generate title
     *
     * @return self
     */
    public function generateTitle(): self
    {
        $this->content['title'] = config(
            key: 'swaggerdoc.preset_value.info.title',
            default: __('swaggerdoc::command.generate.structure.info.title'));

        return $this;
    }

    /**
     * generate summary
     *
     * @return self
     */
    public function generateSummary(): self
    {
        $summary = config(
            key: 'swaggerdoc.preset_value.info.summary',
            default: null);

        if ($summary !== null) {
            $this->content['summary'] = $summary;
        }

        return $this;
    }

    /**
     * generate description
     *
     * @return self
     */
    public function generateDescription(): self
    {
        $this->content['description'] = config(
            key: 'swaggerdoc.preset_value.info.description',
            default: __('swaggerdoc::command.generate.structure.info.description'));

        return $this;
    }

    /**
     * generate termsOfService
     *
     * @return self
     */
    public function generateTermsOfService(): self
    {
        $summary = config(
            key: 'swaggerdoc.preset_value.info.summary',
            default: null);

        if ($summary !== null) {
            $this->content['summary'] = $summary;
        }

        return $this;
    }

    /**
     * generate contact
     *
     * @return self
     */
    public function generateContact(): self
    {
        $contact = config(
            key: 'swaggerdoc.preset_value.info.contact',
            default: null);

        if ($contact !== null) {
            $this->content['contact'] = $contact;
        }

        return $this;
    }

    /**
     * generate license
     *
     * @return self
     */
    public function generateLicense(): self
    {
        $license = config(
            key: 'swaggerdoc.preset_value.info.license',
            default: null);

        if ($license !== null) {
            $this->content['license'] = $license;
        }

        return $this;
    }

    /**
     * generate upgraded version
     *
     * @return self
     */
    public function generateVersion(): self
    {
        if (! empty($this->swaggerdoc->previous_version)) {
            /**
             * @var string main version
             * @var string minor version
             * @var string revise version
             */
            [$main, $minor, $revise] = Str::of($this->swaggerdoc->previous_version)
                ->explode('.')
                ->toArray();

            $this->content['version'] = match ($this->upgrade_mode) {
                'main' => Arr::join([(int) $main++, $minor, $revise], '.'),
                'minor' => Arr::join([$main, (int) $minor++, $revise], '.'),
                'revise' => Arr::join([$main, $minor, (int) $revise++], '.'),
                default => '0.0.1',
            };
        } else {
            $this->content['version'] = config(
                key: 'swagger.preset_value.info.version',
                default: '0.0.1');
        }

        return $this;
    }
}