<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest;

use Generated\Shared\Transfer\RestVersionTransfer;
use Spryker\Glue\GlueApplication\Rest\Collection\ResourceRouteCollection;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceVersionableInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceWithParentPluginInterface;
use Spryker\Glue\Kernel\ModuleNameAwareInterface;
use Symfony\Component\HttpFoundation\Request;

class ResourceRouteLoader implements ResourceRouteLoaderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface[]
     */
    protected $resourcePlugins = [];

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface
     */
    protected $versionResolver;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface[] $resourcePlugins
     * @param \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface $versionResolver
     */
    public function __construct(array $resourcePlugins, VersionResolverInterface $versionResolver)
    {
        $this->resourcePlugins = $resourcePlugins;
        $this->versionResolver = $versionResolver;
    }

    /**
     * @param string $resourceType
     * @param array $resources
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return null|array
     */
    public function load(string $resourceType, array $resources, Request $httpRequest): ?array
    {
        $resourcePlugin = $this->findResourcePlugin($resourceType, $resources, $httpRequest);

        if ($resourcePlugin === null) {
            return null;
        }

        $resourceRouteCollection = $resourcePlugin->configure($this->createResourceRouteCollection());

        $method = $httpRequest->getMethod();
        if (!$resourceRouteCollection->has($method)) {
            return null;
        }

        $resourceConfiguration = [
            RequestConstantsInterface::ATTRIBUTE_TYPE => $resourceType,
            RequestConstantsInterface::ATTRIBUTE_MODULE => $this->getModuleName($resourcePlugin),
            RequestConstantsInterface::ATTRIBUTE_CONTROLLER => $resourcePlugin->getController(),
            RequestConstantsInterface::ATTRIBUTE_CONFIGURATION => $resourceRouteCollection->get($method),
            RequestConstantsInterface::ATTRIBUTE_RESOURCE_FQCN => $resourcePlugin->getResourceAttributesClassName(),
        ];

        if ($resourcePlugin instanceof ResourceWithParentPluginInterface) {
            $resourceConfiguration[RequestConstantsInterface::ATTRIBUTE_PARENT_RESOURCE] = $resourcePlugin->getParentResourceType();
        }

        if ($resourcePlugin instanceof ResourceVersionableInterface) {
            $resourceConfiguration[RequestConstantsInterface::ATTRIBUTE_RESOURCE_VERSION] = $resourcePlugin->getVersion();
        }

        return $resourceConfiguration;
    }

    /**
     * @param string $resourceType
     * @param array $resources
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return array
     */
    public function getAvailableMethods(string $resourceType, array $resources, Request $httpRequest): array
    {
        $resourcePlugin = $this->findResourcePlugin($resourceType, $resources, $httpRequest);

        if ($resourcePlugin === null) {
            return [];
        }

        $resourceRouteCollection = $resourcePlugin->configure($this->createResourceRouteCollection());

        return array_merge($resourceRouteCollection->getAvailableMethods(), [Request::METHOD_OPTIONS]);
    }

    /**
     * @param string $resourceType
     * @param array $resources
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface|null
     */
    protected function findResourcePlugin(string $resourceType, array $resources, Request $httpRequest): ?ResourceRoutePluginInterface
    {
        $resourcePlugins = [];
        foreach ($this->resourcePlugins as $resourceRoutePlugin) {
            if (!$this->isCurrentResourceRoutePlugin($resourceRoutePlugin, $resourceType, $resources)) {
                continue;
            }

            $resourcePlugins[] = $resourceRoutePlugin;
        }

        $requestedVersionTransfer = $this->versionResolver->findVersion($httpRequest);
        if ($requestedVersionTransfer->getMajor()) {
            return $this->findByVersion($resourcePlugins, $requestedVersionTransfer);
        }

        if (count($resourcePlugins) === 1) {
            return $resourcePlugins[0];
        }

        $resourcePlugin = $this->findNewestPluginVersion($resourcePlugins);
        if (!($resourcePlugin instanceof ResourceRoutePluginInterface)) {
            return null;
        }

        return $resourcePlugin;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     * @param string $resourceType
     * @param array $resources
     *
     * @return bool
     */
    protected function isCurrentResourceRoutePlugin(
        ResourceRoutePluginInterface $resourceRoutePlugin,
        string $resourceType,
        array $resources
    ): bool {

        if ($resourceRoutePlugin->getResourceType() !== $resourceType) {
            return false;
        }

        if ($resourceRoutePlugin instanceof ResourceWithParentPluginInterface) {
            $parentResourceType = $resourceRoutePlugin->getParentResourceType();
            return $this->isParentResourceMatching($resources, $parentResourceType);
        }

        return true;
    }

    /**
     * @param array $resources
     * @param string $parentResourceType
     *
     * @return bool
     */
    protected function isParentResourceMatching(array $resources, string $parentResourceType): bool
    {
        foreach ($resources as $resource) {
            if ($resource[RequestConstantsInterface::ATTRIBUTE_TYPE] === $parentResourceType) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    protected function createResourceRouteCollection(): ResourceRouteCollectionInterface
    {
        return new ResourceRouteCollection();
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     * @param \Generated\Shared\Transfer\RestVersionTransfer $requestedVersionTransfer
     *
     * @return bool
     */
    protected function compareVersions(
        ResourceRoutePluginInterface $resourceRoutePlugin,
        RestVersionTransfer $requestedVersionTransfer
    ): bool {

        if (!($resourceRoutePlugin instanceof ResourceVersionableInterface)) {
            return false;
        }

        $resourceVersion = $resourceRoutePlugin->getVersion();

        return ($resourceVersion->getMajor() === $requestedVersionTransfer->getMajor() &&
               $resourceVersion->getMinor() === $requestedVersionTransfer->getMinor());
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface[] $resourcePlugins
     * @param \Generated\Shared\Transfer\RestVersionTransfer $requestedVersionTransfer
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface|null
     */
    protected function findByVersion(
        array $resourcePlugins,
        RestVersionTransfer $requestedVersionTransfer
    ): ?ResourceRoutePluginInterface {

        foreach ($resourcePlugins as $resourcePlugin) {
            if ($this->compareVersions($resourcePlugin, $requestedVersionTransfer)) {
                return $resourcePlugin;
            }
        }

        return null;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceVersionableInterface[] $resourcePlugins
     *
     * @return null|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceVersionableInterface
     */
    protected function findNewestPluginVersion(array $resourcePlugins): ?ResourceVersionableInterface
    {
        $newestVersionPlugin = null;

        foreach ($resourcePlugins as $resourcePlugin) {
            if (!($resourcePlugin instanceof ResourceVersionableInterface)) {
                continue;
            }

            if (!$newestVersionPlugin) {
                $newestVersionPlugin = $resourcePlugin;
            }

            $resourceVersion = $resourcePlugin->getVersion()->getMajor() . $resourcePlugin->getVersion()->getMinor();
            $newestVersion = $newestVersionPlugin->getVersion()->getMajor() . $newestVersionPlugin->getVersion()->getMinor();

            if ($resourceVersion > $newestVersion) {
                $newestVersionPlugin = $resourcePlugin;
            }
        }
        return $newestVersionPlugin;
    }

    /**
     * @param \Spryker\Glue\Kernel\ModuleNameAwareInterface $resourcePlugin
     *
     * @return string
     */
    protected function getModuleName(ModuleNameAwareInterface $resourcePlugin): string
    {
        return $resourcePlugin->getModuleName();
    }
}
