<?php

namespace Cirlmcesc\LaravelSwaggerdoc;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class LaravelSwaggerdoc
{
    /**
     * documentation_data variable
     *
     * @var array
     */
    private $documentation_data = [];

    /**
     * construct function
     */
    public function __construct()
    {
        $this->documentation_data = $this->readJsonFile();
    }

    /**
     * readJsonFile function
     *
     * @return array
     */
    public function readJsonFile(): array
    {
        $json_file_path = config('swaggerdoc.json_path', 'documentation/api-docs.json');

        return file_exists($json_file_path) == true
            ? json_decode(file_get_contents(storage_path($json_file_path)), true)
            : config('swaggerdoc.structure');
    }

    /**
     * writeJsonFile function
     *
     * @param array $json
     * @return self
     */
    public function writeJsonFile(array $json): self
    {
        Storage::delete(config('swaggerdoc.json_path', 'documentation/api-docs.json'));

        Storage::prepend(config('swaggerdoc.json_path', 'documentation/api-docs.json'), json_encode($json));

        return $this;
    }

    /**
     * readYamlFile function
     *
     * @return array
     */
    public function readYamlFile(): array
    {
        try {
            return Yaml::parse(file_get_contents(
                config('swaggerdoc.yaml_path', 'documentation/api-docs.yaml')));
        } catch (ParseException $e) {
            return config('swaggerdoc.structure');
        }
    }

    /**
     * writeYamlFile function
     *
     * @param array $json
     * @return self
     */
    public function writeYamlFile(array $json): self
    {
        Storage::delete(config('swaggerdoc.yaml_path', 'documentation/api-docs.yaml'));

        Storage::prepend(config('swaggerdoc.yaml_path', 'documentation/api-docs.yaml'), Yaml::dump($json));

        return $this;
    }

    /**
     * addToPaths function
     *
     * @param string $path
     * @param string $method
     * @return self
     */
    public function addToPaths(string $path, string $method, array $data): self
    {
        $this->documentation_data = data_set($this->documentation_data, "paths.{$path}.{$method}", $data);

        return $this;
    }

    /**
     * save function
     *
     * @return self
     */
    public function save(): self
    {
        return $this->writeJsonFile($this->documentation_data)
            ->writeYamlFile($this->documentation_data);
    }
}
