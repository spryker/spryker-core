<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Spryker\Glue\GlueApplication\Rest\Collection\ResourceRouteCollection;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Annotation\GlueAnnotationAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestApiDocumentationPathGenerator implements RestApiDocumentationPathGeneratorInterface
{
    protected const REST_ERROR_SCHEMA_NAME = 'RestErrorMessage';

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
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Annotation\GlueAnnotationAnalyzerInterface
     */
    protected $annotationsAnalyser;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig $config
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface $schemaGenerator
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Annotation\GlueAnnotationAnalyzerInterface $annotationsAnalyser
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
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     * @param array|null $parents
     *
     * @return void
     */
    public function addPathsForPlugin(ResourceRoutePluginInterface $resourceRoutePlugin, ?array $parents = null): void
    {
        $resource = $resourceRoutePlugin->getResourceType();
        $collection = $resourceRoutePlugin->configure(new ResourceRouteCollection());
        $annotationParameters = $this->annotationsAnalyser->getParametersFromPlugin($resourceRoutePlugin);

        $resourcePath = $this->parseParentToPath('/' . $resource, $parents);
        $restErrorTransferSchemaKey = $this->schemaGenerator->getRestErrorSchemaName();
        if ($collection->has(Request::METHOD_GET)) {
            if ($annotationParameters && isset($annotationParameters['getCollection']) && $annotationParameters['getCollection']) {
                $responseSchemaKey = $this->schemaGenerator->addResponseCollectionSchemaForPlugin($resourceRoutePlugin);
                $this->paths[$resourcePath]['get'] = [
                    'summary' => "List all $resource",
                    'tags' => [$resource],
                    'responses' => [
                        (string)Response::HTTP_OK => $this->getDefaultSuccessResponse($responseSchemaKey),
                        'default' => $this->getDefaultErrorResponse($restErrorTransferSchemaKey),
                    ],
                ];
            }

            if ($annotationParameters && isset($annotationParameters['getResource']) && $annotationParameters['getResource']) {
                $responseSchemaKey = $this->schemaGenerator->addResponseResourceSchemaForPlugin($resourceRoutePlugin);
                $this->paths[$resourcePath]['get'] = [
                    'summary' => "List all $resource",
                    'tags' => [$resource],
                    'responses' => [
                        (string)Response::HTTP_OK => $this->getDefaultSuccessResponse($responseSchemaKey),
                        'default' => $this->getDefaultErrorResponse($restErrorTransferSchemaKey),
                    ],
                ];
            }
        }
        if ($collection->has(Request::METHOD_POST)) {
            $responseSchemaKey = $this->schemaGenerator->addResponseResourceSchemaForPlugin($resourceRoutePlugin);
            $this->paths[$resourcePath]['post'] = [
                'summary' => "Create $resource",
                'tags' => [$resource],
                'responses' => [
                    (string)Response::HTTP_CREATED => $this->getDefaultSuccessResponse($responseSchemaKey),
                    'default' => $this->getDefaultErrorResponse($restErrorTransferSchemaKey),
                ],
            ];
        }
        if ($collection->has(Request::METHOD_PATCH)) {
            $responseSchemaKey = $this->schemaGenerator->addResponseResourceSchemaForPlugin($resourceRoutePlugin);
            $this->paths[$resourcePath]['patch'] = [
                'summary' => "Update $resource",
                'tags' => [$resource],
                'responses' => [
                    (string)Response::HTTP_ACCEPTED => $this->getDefaultSuccessResponse($responseSchemaKey),
                    'default' => $this->getDefaultErrorResponse($restErrorTransferSchemaKey),
                ],
            ];
        }
        if ($collection->has(Request::METHOD_DELETE)) {
            $this->paths[$resourcePath]['delete'] = [
                'summary' => "Delete $resource",
                'tags' => [$resource],
                'responses' => [
                    (string)Response::HTTP_NO_CONTENT => [
                        'description' => 'Expected response to a valid request',
                    ],
                    'default' => $this->getDefaultErrorResponse($restErrorTransferSchemaKey),
                ],
            ];
        }
    }

    /**
     * @param string $transferClassName
     *
     * @return array
     */
    protected function getDefaultSuccessResponse(string $transferClassName): array
    {
        return $this->getDefaultResponse('Expected response to a valid request', $transferClassName);
    }

    /**
     * @param string $transferClassName
     *
     * @return array
     */
    protected function getDefaultErrorResponse(string $transferClassName): array
    {
        return $this->getDefaultResponse('Expected response to a bad request', $transferClassName);
    }

    /**
     * @param string $description
     * @param string $transferClassName
     *
     * @return array
     */
    protected function getDefaultResponse(string $description, string $transferClassName): array
    {
        return [
            'description' => $description,
            'content' => [
                'application/json' => [
                    'schema' => [
                        '$ref' => sprintf('#/components/schemas/%s', $transferClassName),
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $path
     * @param array|null $parent
     *
     * @return string
     */
    protected function parseParentToPath(string $path, ?array $parent): string
    {
        if (!$parent) {
            return $path;
        }

        return $this->parseParentToPath('/' . $parent['name'] . '/' . $parent['id'] . $path, $parent['parent']);
    }
}
