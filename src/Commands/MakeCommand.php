<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Cirlmcesc\LaravelSwaggerdoc\LaravelSwaggerdoc;

use function Laravel\Prompts\multiselect;

class MakeCommand extends GeneratorCommand implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "make:doc {filename} {--json} {--yaml}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate a new Swagger documentation file";

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Documentation';

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array<string, string>
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'filename' => __("swaggerdoc::command.make.file_name.required"),
        ];
    }

    /**
     * Get the stub file for the generator.
     *
     * @return void
     */
    protected function getStub()
    {
        return $this->resolveStubPath('');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function  handle()
    {
        /** @var array $file_type_options */
        $file_type_options = multiselect(
            label: __("swaggerdoc::command.make.file_type.label"), 
            options: ["json", "yaml"],
            required: __("swaggerdoc::command.make.file_type.required"));

        /** @var string $file_name */
        $file_name = $this->argument("filename");
        
        // resolve(LaravelSwaggerdoc::class)->make(
        //     file_name: $file_name,
        //     need_json: in_array('json', $file_type_options),
        //     need_yaml: in_array('yaml', $file_type_options),
        // );
    }
}