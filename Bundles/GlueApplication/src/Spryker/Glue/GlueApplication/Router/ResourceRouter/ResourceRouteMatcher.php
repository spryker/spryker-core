<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router\ResourceRouter;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Resource\MissingResource;
use Spryker\Glue\GlueApplication\Router\ResourceRouter\Uri\UriParserInterface;
use Spryker\Glue\GlueApplication\Router\RouteMatcherInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourcesProviderPluginInterface;
use Symfony\Component\HttpFoundation\Request;

class ResourceRouteMatcher implements RouteMatcherInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Router\ResourceRouter\Uri\UriParserInterface
     */
    protected UriParserInterface $uriParser;

    /**
     * @var \Spryker\Glue\GlueApplication\Router\ResourceRouter\RequestResourcePluginFilterInterface
     */
    protected RequestResourcePluginFilterInterface $requestResourcePluginFilter;

    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourcesProviderPluginInterface>
     */
    protected array $resourcesProviderPlugins;

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourcesProviderPluginInterface> $resourcesProviderPlugins
     * @param \Spryker\Glue\GlueApplication\Router\ResourceRouter\Uri\UriParserInterface $uriParser
     * @param \Spryker\Glue\GlueApplication\Router\ResourceRouter\RequestResourcePluginFilterInterface $requestResourcePluginFilter
     */
    public function __construct(
        array $resourcesProviderPlugins,
        UriParserInterface $uriParser,
        RequestResourcePluginFilterInterface $requestResourcePluginFilter
    ) {
        $this->resourcesProviderPlugins = $resourcesProviderPlugins;
        $this->uriParser = $uriParser;
        $this->requestResourcePluginFilter = $requestResourcePluginFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    public function route(GlueRequestTransfer $glueRequestTransfer): ResourceInterface
    {
        $resources = $this->uriParser->parse($glueRequestTransfer->getPath());

        if ($resources === null) {
            return new MissingResource(
                GlueApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
                GlueApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
            );
        }
        $mainResource = array_pop($resources);
        $mainGlueResourceTransfer = (new GlueResourceTransfer())
            ->setResourceName($mainResource['type'])
            ->setType($mainResource['type'])
            ->setId($mainResource['id'])
            ->setMethod(strtolower($glueRequestTransfer->getMethod()));
        if ($mainGlueResourceTransfer->getMethod() === strtolower(Request::METHOD_GET) && !$mainGlueResourceTransfer->getId()) {
            $mainGlueResourceTransfer->setMethod('getCollection');
        }
        $glueRequestTransfer->setResource($mainGlueResourceTransfer);

        foreach ($resources as $resource) {
            $glueRequestTransfer->addParentResource(
                $resource['type'],
                (new GlueResourceTransfer())
                    ->setResourceName($resource['type'])
                    ->setType($resource['type'])
                    ->setId($resource['id'])
                    ->setMethod(strtolower($glueRequestTransfer->getMethod())),
            );
        }

        $resourceProviderPlugin = $this->findResourcesProvider($glueRequestTransfer->getApplicationOrFail());

        if (!$resourceProviderPlugin) {
            return new MissingResource(
                GlueApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
                GlueApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
            );
        }

        return $this->loadResource($glueRequestTransfer, $resourceProviderPlugin->getResources());
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resourcePlugins
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    protected function loadResource(GlueRequestTransfer $glueRequestTransfer, array $resourcePlugins): ResourceInterface
    {
        $resourcePlugin = $this->requestResourcePluginFilter->filterResourcePlugins($glueRequestTransfer, $resourcePlugins);

        if (!$resourcePlugin) {
            return new MissingResource(
                GlueApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
                GlueApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
            );
        }

        $glueResourceMethodCollectionTransfer = $resourcePlugin->getDeclaredMethods();

        if (
            !$glueResourceMethodCollectionTransfer->offsetGet($glueRequestTransfer->getResource()->getMethod()) &&
            $glueRequestTransfer->getMethod() !== Request::METHOD_OPTIONS
        ) {
            return new MissingResource(
                GlueApplicationConfig::ERROR_CODE_METHOD_NOT_FOUND,
                GlueApplicationConfig::ERROR_MESSAGE_METHOD_NOT_FOUND,
            );
        }

        return $resourcePlugin;
    }

    /**
     * @param string $apiApplicationName
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourcesProviderPluginInterface|null
     */
    protected function findResourcesProvider(string $apiApplicationName): ?ResourcesProviderPluginInterface
    {
        foreach ($this->resourcesProviderPlugins as $resourcesProviderPlugin) {
            if ($resourcesProviderPlugin->getApplicationName() !== $apiApplicationName) {
                continue;
            }

            return $resourcesProviderPlugin;
        }

        return null;
    }
}
