<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig;
use Symfony\Component\HttpFoundation\Response;

class RestApiDocumentationPathGenerator implements RestApiDocumentationPathGeneratorInterface
{
    protected const PATTERN_SCHEMA_REFERENCE = '#/components/schemas/%s';
    protected const PATTERN_REGEX_RESOURCE_ID = '/(?<=\{).+?(?=\})/';
    protected const PATTERN_DESCRIPTION_PARAMETER_ID = 'Id of %s.';

    protected const METHOD_GET = 'get';
    protected const METHOD_POST = 'post';
    protected const METHOD_PATCH = 'patch';
    protected const METHOD_DELETE = 'delete';

    protected const DESCRIPTION_DEFAULT_RESPONSE = 'Expected response to a bad request';
    protected const DESCRIPTION_SUCCESSFUL_RESPONSE = 'Expected response to a valid request';

    protected const KEY_APPLICATION_JSON = 'application/json';
    protected const KEY_BEARER_AUTH = 'BearerAuth';
    protected const KEY_CONTENT = 'content';
    protected const KEY_DEFAULT1 = 'default';
    protected const KEY_DESCRIPTION = 'description';
    protected const KEY_IN = 'in';
    protected const KEY_NAME = 'name';
    protected const KEY_REF = '$ref';
    protected const KEY_REQUEST_BODY = 'requestBody';
    protected const KEY_REQUIRED = 'required';
    protected const KEY_RESPONSES = 'responses';
    protected const KEY_SCHEMA = 'schema';
    protected const KEY_SECURITY = 'security';
    protected const KEY_SUMMARY = 'summary';
    protected const KEY_TAGS = 'tags';
    protected const KEY_TYPE = 'type';

    /**
     * @var array
     */
    protected $paths = [];

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface
     */
    protected $schemaGenerator;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzerInterface
     */
    protected $annotationsAnalyser;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig $config
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface $schemaGenerator
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzerInterface $annotationsAnalyser
     */
    public function __construct(
        RestApiDocumentationGeneratorConfig $config,
        RestApiDocumentationSchemaGeneratorInterface $schemaGenerator,
        GlueAnnotationAnalyzerInterface $annotationsAnalyser
    ) {
        $this->config = $config;
        $this->schemaGenerator = $schemaGenerator;
        $this->annotationsAnalyser = $annotationsAnalyser;
    }

    /**
     * @return array
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @param string $resource
     * @param string $resourcePath
     * @param string $responseSchema
     * @param string $errorSchema
     * @param bool $isProtected
     *
     * @return void
     */
    public function addGetPath(string $resource, string $resourcePath, string $responseSchema, string $errorSchema, bool $isProtected): void
    {
        $summary = 'Get ' . $resource;
        $this->addPathDefaultInfo(static::METHOD_GET, $resource, $resourcePath, $summary, $isProtected);
        $this->addPathRequiredPathParameters(static::METHOD_GET, $resourcePath, $this->getIdParametersFromResourcePath($resourcePath));
        $this->addPathCustomResponse(static::METHOD_GET, (string)Response::HTTP_OK, $resourcePath, static::DESCRIPTION_SUCCESSFUL_RESPONSE, $responseSchema);
        $this->addPathDefaultResponse(static::METHOD_GET, $resourcePath, $errorSchema);
    }

    /**
     * @param string $resource
     * @param string $resourcePath
     * @param string $requestSchema
     * @param string $responseSchema
     * @param string $errorSchema
     * @param bool $isProtected
     *
     * @return void
     */
    public function addPostPath(string $resource, string $resourcePath, string $requestSchema, string $responseSchema, string $errorSchema, bool $isProtected): void
    {
        $summary = 'Add ' . $resource;
        $this->addPathDefaultInfo(static::METHOD_POST, $resource, $resourcePath, $summary, $isProtected);
        $this->addPathRequiredPathParameters(static::METHOD_POST, $resourcePath, $this->getIdParametersFromResourcePath($resourcePath));
        $this->addPathRequestBody(static::METHOD_POST, $resourcePath, $requestSchema);
        $this->addPathCustomResponse(static::METHOD_GET, (string)Response::HTTP_CREATED, $resourcePath, static::DESCRIPTION_SUCCESSFUL_RESPONSE, $responseSchema);
        $this->addPathDefaultResponse(static::METHOD_GET, $resourcePath, $errorSchema);
    }

    /**
     * @param string $resource
     * @param string $resourcePath
     * @param string $requestSchema
     * @param string $responseSchema
     * @param string $errorSchema
     * @param bool $isProtected
     *
     * @return void
     */
    public function addPatchPath(string $resource, string $resourcePath, string $requestSchema, string $responseSchema, string $errorSchema, bool $isProtected): void
    {
        $summary = 'Update ' . $resource;
        $this->addPathDefaultInfo(static::METHOD_PATCH, $resource, $resourcePath, $summary, $isProtected);
        $this->addPathRequiredPathParameters(static::METHOD_PATCH, $resourcePath, $this->getIdParametersFromResourcePath($resourcePath));
        $this->addPathRequestBody(static::METHOD_PATCH, $resourcePath, $requestSchema);
        $this->addPathCustomResponse(static::METHOD_GET, (string)Response::HTTP_ACCEPTED, $resourcePath, static::DESCRIPTION_SUCCESSFUL_RESPONSE, $responseSchema);
        $this->addPathDefaultResponse(static::METHOD_GET, $resourcePath, $errorSchema);
    }

    /**
     * @param string $resource
     * @param string $resourcePath
     * @param string $errorSchema
     * @param bool $isProtected
     *
     * @return void
     */
    public function addDeletePath(string $resource, string $resourcePath, string $errorSchema, bool $isProtected): void
    {
        $summary = 'Delete ' . $resource;
        $this->addPathDefaultInfo(static::METHOD_DELETE, $resource, $resourcePath, $summary, $isProtected);
        $this->addPathRequiredPathParameters(static::METHOD_DELETE, $resourcePath, $this->getIdParametersFromResourcePath($resourcePath));
        $this->addPathCustomResponseWithoutContent(static::METHOD_DELETE, (string)Response::HTTP_NO_CONTENT, $resourcePath, static::DESCRIPTION_SUCCESSFUL_RESPONSE);
    }

    /**
     * @param string $method
     * @param string $resource
     * @param string $resourcePath
     * @param string $summary
     * @param bool $isProtected
     *
     * @return void
     */
    protected function addPathDefaultInfo(string $method, string $resource, string $resourcePath, string $summary, bool $isProtected): void
    {
        $this->paths[$resourcePath][$method][self::KEY_SUMMARY] = $summary;
        $this->paths[$resourcePath][$method][self::KEY_TAGS] = [$resource];
        if ($isProtected) {
            $this->paths[$resourcePath][$method][self::KEY_SECURITY] = [self::KEY_BEARER_AUTH => []];
        }
    }

    /**
     * @param string $method
     * @param string $resourcePath
     * @param array $requiredParameters
     *
     * @return void
     */
    protected function addPathRequiredPathParameters(string $method, string $resourcePath, array $requiredParameters): void
    {
        if (!$requiredParameters) {
            return;
        }

        foreach ($requiredParameters as $parameter) {
            $this->paths[$resourcePath][$method]['parameters'][] = [
                static::KEY_NAME => $parameter,
                static::KEY_IN => 'path',
                static::KEY_DESCRIPTION => $this->getDescriptionFromIdParameter($resourcePath),
                static::KEY_REQUIRED => true,
                static::KEY_SCHEMA => [
                    static::KEY_TYPE => 'string',
                ],
            ];
        }
    }

    /**
     * @param string $parameter
     *
     * @return string
     */
    protected function getDescriptionFromIdParameter(string $parameter): string
    {
        $parameterSplitted = array_slice(preg_split('/(?=[A-Z])/', $parameter), 0, -1);
        $parameterSplitted = array_map('lcfirst', $parameterSplitted);

        return sprintf(static::PATTERN_DESCRIPTION_PARAMETER_ID, implode(' ', $parameterSplitted));
    }

    /**
     * @param string $method
     * @param string $resourcePath
     * @param string $requestSchema
     *
     * @return void
     */
    protected function addPathRequestBody(string $method, string $resourcePath, string $requestSchema): void
    {
        $this->paths[$resourcePath][$method][self::KEY_REQUEST_BODY] = $this->getRequestResponse(self::KEY_DESCRIPTION, $requestSchema);
    }

    /**
     * @param string $method
     * @param string $code
     * @param string $resourcePath
     * @param string $description
     * @param string $responseSchema
     *
     * @return void
     */
    protected function addPathCustomResponse(string $method, string $code, string $resourcePath, string $description, string $responseSchema): void
    {
        $this->paths[$resourcePath][$method][self::KEY_RESPONSES][$code] = $this->getDefaultResponseWithContent($description, $responseSchema);
    }

    /**
     * @param string $method
     * @param string $code
     * @param string $resourcePath
     * @param string $description
     *
     * @return void
     */
    protected function addPathCustomResponseWithoutContent(string $method, string $code, string $resourcePath, string $description): void
    {
        $this->paths[$resourcePath][$method][self::KEY_RESPONSES][$code] = $this->getDefaultResponseWithoutContent($description);
    }

    /**
     * @param string $method
     * @param string $resourcePath
     * @param string $errorSchema
     *
     * @return void
     */
    protected function addPathDefaultResponse(string $method, string $resourcePath, string $errorSchema): void
    {
        $this->paths[$resourcePath][$method][self::KEY_RESPONSES][self::KEY_DEFAULT1] = $this->getDefaultResponseWithContent(static::DESCRIPTION_DEFAULT_RESPONSE, $errorSchema);
    }

    /**
     * @param string $description
     * @param string $schemaName
     *
     * @return array
     */
    protected function getRequestResponse(string $description, string $schemaName): array
    {
        return array_merge([self::KEY_REQUIRED => true], $this->getDefaultResponseWithContent($description, $schemaName));
    }

    /**
     * @param string $description
     *
     * @return array
     */
    protected function getDefaultResponseWithoutContent(string $description): array
    {
        return [
            self::KEY_DESCRIPTION => $description,
        ];
    }

    /**
     * @param string $description
     * @param string $schemaName
     *
     * @return array
     */
    protected function getDefaultResponseWithContent(string $description, string $schemaName): array
    {
        return array_merge($this->getDefaultResponseWithoutContent($description), [
            self::KEY_CONTENT => [
                self::KEY_APPLICATION_JSON => [
                    self::KEY_SCHEMA => [
                        self::KEY_REF => sprintf(static::PATTERN_SCHEMA_REFERENCE, $schemaName),
                    ],
                ],
            ],
        ]);
    }

    /**
     * @param string $resourcePath
     *
     * @return array
     */
    protected function getIdParametersFromResourcePath(string $resourcePath): array
    {
        preg_match(static::PATTERN_REGEX_RESOURCE_ID, $resourcePath, $matches);

        return $matches;
    }
}
