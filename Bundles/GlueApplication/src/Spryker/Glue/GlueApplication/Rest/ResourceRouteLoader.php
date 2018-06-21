<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest;

use Generated\Shared\Transfer\RestVersionTransfer;
use Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRouteCollection;
use Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceVersionableInterface;
use Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceWithParentPluginInterface;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Spryker\Glue\Kernel\ModuleNameAwareInterface;
use Symfony\Component\HttpFoundation\Request;

class ResourceRouteLoader implements ResourceRouteLoaderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRoutePluginInterface[]
     */
    protected $resourcePlugins = [];

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface
     */
    protected $versionResolver;

    /**
     * @param \Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRoutePluginInterface[] $resourcePlugins
     * @param \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface $versionResolver
     */
    public function __construct(array $resourcePlugins, VersionResolverInterface $versionResolver)
    {
        $this->resourcePlugins = $resourcePlugins;
        $this->versionResolver = $versionResolver;
    }

    /**
     * @param string $resourceType
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return null|array
     */
    public function load(string $resourceType, Request $httpRequest): ?array
    {
        $resourcePlugin = $this->findResourcePlugin($resourceType, $httpRequest);

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
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return array
     */
    public function getAvailableMethods(string $resourceType, Request $httpRequest): array
    {
        $resourcePlugin = $this->findResourcePlugin($resourceType, $httpRequest);

        if ($resourcePlugin === null) {
            return null;
        }

        $resourceRouteCollection = $resourcePlugin->configure($this->createResourceRouteCollection());

        return array_merge($resourceRouteCollection->getAvailableMethods(), [Request::METHOD_OPTIONS]);
    }

    /**
     * @param string $resourceType
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return \Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRoutePluginInterface|null
     */
    protected function findResourcePlugin(string $resourceType, Request $httpRequest): ?ResourceRoutePluginInterface
    {
        $resourcePlugins = [];
        foreach ($this->resourcePlugins as $resourceProviderPlugin) {
            if ($resourceProviderPlugin->getResourceType() !== $resourceType) {
                continue;
            }

            $resourcePlugins[] = $resourceProviderPlugin;
        }

        $requestedVersionTransfer = $this->versionResolver->findVersion($httpRequest);
        if ($requestedVersionTransfer->getMajor()) {
            return $this->findByVersion($resourcePlugins, $requestedVersionTransfer);
        }

        if (count($resourcePlugins) === 1) {
            return $resourcePlugins[0];
        }

        return $this->findNewestPluginVersion($resourcePlugins);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    protected function createResourceRouteCollection(): ResourceRouteCollectionInterface
    {
        return new ResourceRouteCollection();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
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

        if ((int)$resourceVersion->getMajor() === (int)$requestedVersionTransfer->getMajor() &&
            (int)$resourceVersion->getMinor() === (int)$requestedVersionTransfer->getMinor()) {
            return true;
        }

        return false;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRoutePluginInterface[] $resourcePlugins
     * @param \Generated\Shared\Transfer\RestVersionTransfer $requestedVersionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRoutePluginInterface|null
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
     * @param \Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRoutePluginInterface[]|\Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceVersionableInterface[] $resourcePlugins
     *
     * @return null|\Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRoutePluginInterface
     */
    protected function findNewestPluginVersion(array $resourcePlugins): ?ResourceRoutePluginInterface
    {
        $newestVersionPlugin = null;
        foreach ($resourcePlugins as $resourcePlugin) {
            if (!($resourcePlugin instanceof ResourceVersionableInterface)) {
                continue;
            }

            if (!$newestVersionPlugin) {
                $newestVersionPlugin = $resourcePlugin;
            }

            $resourceVersion = (int)$resourcePlugin->getVersion()->getMajor() . $resourcePlugin->getVersion()->getMinor();
            $newestVersion = (int)$newestVersionPlugin->getVersion()->getMajor() . $newestVersionPlugin->getVersion()->getMinor();

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
