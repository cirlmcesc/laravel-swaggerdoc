<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Traits;

use Illuminate\Testing\TestResponse;
use Illuminate\Support\Traits\Macroable;
use Cirlmcesc\LaravelSwaggerdoc\LaravelSwaggerdoc;

trait Swaggerdocable
{
    use Macroable;

    /**
     * api descprition variable
     *
     * @var array
     */
    protected $api_descprition = [];

    /**
     * api test data variable
     *
     * @var array
     */
    protected $api_test_data = [];

    /**
     * request method variable
     *
     * @var string
     */
    protected $request_method = '';

    /**
     * request path variable
     *
     * @var string
     */
    protected $request_path = '';

    /**
     * oauth function
     *
     * @return mixed
     */
    abstract protected function oauth();

    /**
     * request function
     *
     * @return mixed
     */
    abstract protected function request();

    /**
     * summary function
     *
     * @return mixed
     */
    protected function summary(string $summary)
    {
        $this->api_descprition['summary'] = $summary;

        return $this;
    }

    /**
     * tags function
     *
     * @param array $tags
     * @return mixed
     */
    protected function tags(array $tags)
    {
        $this->api_descprition['tags'] = $tags;

        return $this;
    }

    /**
     * description function
     *
     * @param string $description
     * @return mixed
     */
    protected function description(string $description)
    {
        $this->api_descprition['description'] = $description;

        return $this;
    }

    /**
     * requestWithHeaders function
     *
     * @return mixed
     */
    protected function requestWithHeaders(array $request_headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ]) {
        $this->withHeaders($request_headers);

        return $this;
    }

    /**
     * method function
     *
     * @param string $method
     * @return mixed
     */
    protected function method(string $method)
    {
        $this->request_method = $method;

        return $this;
    }

    /**
     * operationId function
     *
     * @param string $operation_id
     * @return mixed
     */
    protected function operationId(string $operation_id)
    {
        $this->api_descprition['operationId'] = $operation_id;

        return $this;
    }

    /**
     * path function
     *
     * @param string $path
     * @return mixed
     */
    protected function path(string $path)
    {
        $this->request_path = $path;

        return $this;
    }

    /**
     * security function
     *
     * @param array $permissions
     * @return mixed
     */
    protected function security(array $permissions)
    {
        $this->api_descprition['security'] = $permissions;

        return $this;
    }

    /**
     * parameters function
     *
     * @param array $parameters
     * @return mixed
     */
    protected function parameters(array $parameters = [])
    {
        $this->api_descprition['parameters'] = empty($parameters)
            ? config('swaggerdoc.query_parameters')
            : $parameters;

        return $this;
    }

    /**
     * requestJsonContentBody function
     *
     * @param array $content
     * @param array $properties
     * @param array $example_data
     * @param array $required
     * @return mixed
     */
    protected function requestJsonContentBody(array $content, array $properties = [], array $example_data = [], $required = []): self
    {
        foreach ($content as $attribute => $value) {
            $properties[$attribute] = [
                'type' => $value['type'],
                'description' => $value['description'],
            ];

            if ($value['type'] == 'array') {
                $properties[$attribute]['items'] = $value['items'];
            }

            if ($value['is_required'] == true) {
                $required[] = $attribute;
            }
        }

        data_set($this->api_descprition,
            "requestBody.content.application/json",
            [
                'schema' => [
                    'type' => 'object',
                    'properties' => $properties,
                    'required' => $required
                ],
                'examples' => [
                    'requestExample' => ['value' => $example_data],
                ],
            ]);

        return $this;
    }

    /**
     * assertResponse function
     *
     * @param TestResponse $response
     * @param array $description
     * @return mixed
     */
    protected function assertStandardResponse(TestResponse $response, array $description)
    {
        $response->assertSuccessful();

        $this->api_descprition['responses'] = config("swaggerdoc.standar_response");

        data_set($this->api_descprition,
            "responses.200.content.application/json.examples.successful.value",
            $response->data);

        return $this;
    }

    /**
     * stamp function
     *
     * @param string $summary
     * @param string $description
     * @param array $tags
     * @return mixed
     */
    protected function stamp(string $summary, string $description, array $tags)
    {
        return $this->summary($summary)->description($description)->tags($tags);
    }

    /**
     * oauthTest function
     *
     * @param string $request_id
     * @param string $method
     * @param string $path
     * @return mixed
     */
    protected function oauthTest(string $request_id, string $method, string $path)
    {
        return $this->oauth()->basicTest(...func_get_args());
    }

    /**
     * basicTest function
     *
     * @param string $request_id
     * @param string $method
     * @param string $path
     * @return mixed
     */
    protected function basicTest(string $request_id, string $method, string $path)
    {
        return $this->operationId($request_id)->method($method)->path($path);
    }
}
