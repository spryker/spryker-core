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
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceWithParentPluginInterface;
use Symfony\Component\HttpFoundation\Request;

class ResourceRouteMatcher implements RouteMatcherInterface
{
    /**
     * @var string
     */
    protected const RESOURCE_TYPE_KEY = 'type';

    /**
     * @var string
     */
    protected const RESOURCE_ID_KEY = 'id';

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
            ->setResourceName($mainResource[static::RESOURCE_TYPE_KEY])
            ->setType($mainResource[static::RESOURCE_TYPE_KEY])
            ->setId($mainResource[static::RESOURCE_ID_KEY])
            ->setMethod(strtolower($glueRequestTransfer->getMethod()));
        if ($mainGlueResourceTransfer->getMethod() === strtolower(Request::METHOD_GET) && !$mainGlueResourceTransfer->getId()) {
            $mainGlueResourceTransfer->setMethod('getCollection');
        }
        $glueRequestTransfer->setResource($mainGlueResourceTransfer);

        $resourceProviderPlugin = $this->findResourcesProvider($glueRequestTransfer->getApplicationOrFail());

        if ($resourceProviderPlugin === null) {
            return new MissingResource(
                GlueApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
                GlueApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
            );
        }

        foreach ($resources as $resource) {
            $parentResourceTransfer = (new GlueResourceTransfer())
                ->setResourceName($resource[static::RESOURCE_TYPE_KEY])
                ->setType($resource[static::RESOURCE_TYPE_KEY])
                ->setId($resource[static::RESOURCE_ID_KEY])
                ->setMethod(strtolower($glueRequestTransfer->getMethod()));

            $parentResource = $this->loadResource($glueRequestTransfer, $resourceProviderPlugin->getResources(), $parentResourceTransfer);

            if ($parentResource instanceof MissingResource) {
                return $parentResource;
            }

            if (!$this->isParentResourceMatching($parentResource, $glueRequestTransfer)) {
                return new MissingResource(
                    GlueApplicationConfig::ERROR_CODE_PARENT_RESOURCE_NOT_FOUND,
                    GlueApplicationConfig::ERROR_MESSAGE_PARENT_RESOURCE_NOT_FOUND,
                );
            }

            $glueRequestTransfer->addParentResource(
                $resource[static::RESOURCE_TYPE_KEY],
                $parentResourceTransfer,
            );
        }

        $mainResource = $this->loadResource($glueRequestTransfer, $resourceProviderPlugin->getResources(), $mainGlueResourceTransfer);

        if (!$this->isParentResourceMatching($mainResource, $glueRequestTransfer)) {
            return new MissingResource(
                GlueApplicationConfig::ERROR_CODE_PARENT_RESOURCE_NOT_FOUND,
                GlueApplicationConfig::ERROR_MESSAGE_PARENT_RESOURCE_NOT_FOUND,
            );
        }

        return $mainResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    protected function isParentResourceMatching(ResourceInterface $resource, GlueRequestTransfer $glueRequestTransfer): bool
    {
        if (!$resource instanceof ResourceWithParentPluginInterface) {
            return true;
        }

        $parentResourceTransfers = $glueRequestTransfer->getParentResources()->getArrayCopy();
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $previousParentTransfer */
        $previousParentTransfer = end($parentResourceTransfers);
        if ($resource->getParentResourceType() === $previousParentTransfer->getTypeOrFail()) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resourcePlugins
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    protected function loadResource(
        GlueRequestTransfer $glueRequestTransfer,
        array $resourcePlugins,
        GlueResourceTransfer $glueResourceTransfer
    ): ResourceInterface {
        $resourcePlugin = $this->requestResourcePluginFilter->filterResourcePlugins($glueRequestTransfer, $resourcePlugins, $glueResourceTransfer);

        if (!$resourcePlugin) {
            return new MissingResource(
                GlueApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
                GlueApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
            );
        }

        $glueResourceMethodCollectionTransfer = $resourcePlugin->getDeclaredMethods();

        if (
            !$glueResourceMethodCollectionTransfer->offsetGet($glueResourceTransfer->getMethod()) &&
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
