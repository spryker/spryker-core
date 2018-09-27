<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component;

class PathMethodSpecificationComponent implements SpecificationComponentInterface
{
    protected const KEY_PARAMETERS = 'parameters';
    protected const KEY_REQUEST_BODY = 'requestBody';
    protected const KEY_RESPONSES = 'responses';
    protected const KEY_SECURITY = 'security';
    protected const KEY_SUMMARY = 'summary';
    protected const KEY_TAGS = 'tags';

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @var array
     */
    protected $tags;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SpecificationComponentInterface[]
     */
    protected $parameters;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SpecificationComponentInterface|null
     */
    protected $request;

    /**
     * @var array
     */
    protected $security;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SpecificationComponentInterface[]
     */
    protected $responses;

    /**
     * @return array
     */
    public function toArray(): array
    {
        $pathData[static::KEY_SUMMARY] = $this->summary;
        $pathData[static::KEY_TAGS] = $this->tags;
        if ($this->parameters) {
            $pathData[static::KEY_PARAMETERS] = array_map(function (SpecificationComponentInterface $parameter) {
                return $parameter->toArray();
            }, $this->parameters);
        }
        if ($this->request) {
            $pathData[static::KEY_REQUEST_BODY] = $this->request->toArray();
        }
        if ($this->security) {
            $pathData[static::KEY_SECURITY] = $this->security;
        }
        $pathData[static::KEY_RESPONSES] = [];
        foreach ($this->responses as $response) {
            $responseData = $response->toArray();
            $pathData[static::KEY_RESPONSES] += $responseData;
            ksort($pathData[static::KEY_RESPONSES], SORT_NATURAL);
        }

        return [$this->method => $pathData];
    }

    /**
     * @return array
     */
    public function getRequiredProperties(): array
    {
        return [
            $this->method,
            $this->summary,
            $this->tags,
            $this->responses,
        ];
    }

    /**
     * @param string $method
     *
     * @return void
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @param string $summary
     *
     * @return void
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @param array $tags
     *
     * @return void
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\PathParameterSpecificationComponent[] $parameters
     *
     * @return void
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\PathRequestSpecificationComponent $request
     *
     * @return void
     */
    public function setRequest(PathRequestSpecificationComponent $request): void
    {
        $this->request = $request;
    }

    /**
     * @param array $security
     *
     * @return void
     */
    public function setSecurity(array $security): void
    {
        $this->security = $security;
    }

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\PathResponseSpecificationComponent[] $responses
     *
     * @return void
     */
    public function setResponses(array $responses): void
    {
        $this->responses = $responses;
    }

    /**
     * @param string $tag
     *
     * @return void
     */
    public function addTag(string $tag): void
    {
        $this->tags[] = $tag;
    }

    /**
     * @param array $security
     *
     * @return void
     */
    public function addSecurity(array $security): void
    {
        $this->security[] = $security;
    }

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\PathParameterSpecificationComponent $parameterPathComponent
     *
     * @return void
     */
    public function addParameter(PathParameterSpecificationComponent $parameterPathComponent): void
    {
        $this->parameters[] = $parameterPathComponent;
    }

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\PathResponseSpecificationComponent $responsePathComponent
     *
     * @return void
     */
    public function addResponse(PathResponseSpecificationComponent $responsePathComponent): void
    {
        $this->responses[] = $responsePathComponent;
    }
}
