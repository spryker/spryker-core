<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Spryker\Glue\GlueApplication\Rest\Collection\ResourceRouteCollection;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToAnnotationsAnalyserInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface;
use Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestApiDocumentationPathGenerator implements RestApiDocumentationPathGeneratorInterface
{
    /**
     * @var array
     */
    protected $paths = [];

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToAnnotationsAnalyserInterface
     */
    protected $annotationsAnalyser;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface
     */
    protected $finder;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig $config
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToAnnotationsAnalyserInterface $annotationsAnalyser
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface $finder
     */
    public function __construct(
        RestApiDocumentationGeneratorConfig $config,
        RestApiDocumentationGeneratorToAnnotationsAnalyserInterface $annotationsAnalyser,
        RestApiDocumentationGeneratorToFinderInterface $finder
    ) {
        $this->config = $config;
        $this->annotationsAnalyser = $annotationsAnalyser;
        $this->finder = $finder;
    }

    /**
     * @return array
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @return void
     */
    public function addPathsFromAnnotations(): void
    {
        $sourceDirTemplates = $this->config->getAnnotationsSourceDirectories();

        $dirs = array_filter($sourceDirTemplates, function ($directory) {
            return (bool)glob($directory, GLOB_ONLYDIR);
        });

        $this->finder->in($dirs)->name('*.php')->sortByName();

        foreach ($this->finder as $file) {
            $this->annotationsAnalyser->analyse($file->getPathname());
        }
        $this->annotationsAnalyser->process();
        $this->annotationsAnalyser->validate();
        $this->paths += $this->annotationsAnalyser->getPaths();
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     * @param string $transferSchemaKey
     * @param string $restErrorTransferSchemaKey
     *
     * @return void
     */
    public function addPathsForPlugin(ResourceRoutePluginInterface $resourceRoutePlugin, string $transferSchemaKey, string $restErrorTransferSchemaKey): void
    {
        $resource = $resourceRoutePlugin->getResourceType();
        $collection = $resourceRoutePlugin->configure(new ResourceRouteCollection());

        if ($collection->has(Request::METHOD_GET)) {
            $this->paths['/' . $resource]['get'] = [
                'summary' => "List all $resource",
                'tags' => [$resource],
                'responses' => [
                    (string)Response::HTTP_OK => $this->getDefaultSuccessResponse($transferSchemaKey),
                    'default' => $this->getDefaultErrorResponse($restErrorTransferSchemaKey),
                ],
            ];
        }
        if ($collection->has(Request::METHOD_POST)) {
            $this->paths['/' . $resource]['post'] = [
                'summary' => "Create $resource",
                'tags' => [$resource],
                'responses' => [
                    (string)Response::HTTP_CREATED => $this->getDefaultSuccessResponse($transferSchemaKey),
                    'default' => $this->getDefaultErrorResponse($restErrorTransferSchemaKey),
                ],
            ];
        }
        if ($collection->has(Request::METHOD_PATCH)) {
            $this->paths['/' . $resource]['patch'] = [
                'summary' => "Update $resource",
                'tags' => [$resource],
                'responses' => [
                    (string)Response::HTTP_ACCEPTED => $this->getDefaultSuccessResponse($transferSchemaKey),
                    'default' => $this->getDefaultErrorResponse($restErrorTransferSchemaKey),
                ],
            ];
        }
        if ($collection->has(Request::METHOD_DELETE)) {
            $this->paths['/' . $resource]['delete'] = [
                'summary' => "Delete $resource",
                'tags' => [$resource],
                'responses' => [
                    (string)Response::HTTP_NO_CONTENT => $this->getDefaultSuccessResponse($transferSchemaKey),
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
}
