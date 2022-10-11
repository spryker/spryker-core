<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router\ResourceRouter;

use Generated\Shared\Transfer\GlueRequestTransfer;
use ReflectionClass;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

class ConventionResourceFilter implements ConventionResourceFilterInterface
{
    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface>
     */
    protected array $conventionPlugins;

    /**
     * @var string
     */
    protected const RESOURCE_INTERFACE_SEARCH_PATTERN = '/\\\ResourceInterface/i';

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface> $conventionPlugins
     */
    public function __construct(array $conventionPlugins)
    {
        $this->conventionPlugins = $conventionPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resources
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    public function filter(GlueRequestTransfer $glueRequestTransfer, array $resources): array
    {
        if (!$glueRequestTransfer->getConvention()) {
            return $this->getDefaultConventionResource($resources);
        }

        $apiConventionPlugin = $this->findApiConventionPluginClassName($glueRequestTransfer->getConvention());

        if (!$apiConventionPlugin) {
            return $resources;
        }

        $resourceType = $apiConventionPlugin->getResourceType();

        return array_filter(
            $resources,
            function (ResourceInterface $resourcePlugin) use ($resourceType): bool {
                return $resourcePlugin instanceof $resourceType;
            },
        );
    }

    /**
     * @param string $conventionName
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null
     */
    protected function findApiConventionPluginClassName(string $conventionName): ?ConventionPluginInterface
    {
        foreach ($this->conventionPlugins as $conventionPlugin) {
            if ($conventionPlugin->getName() === $conventionName) {
                return $conventionPlugin;
            }
        }

        return null;
    }

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resources
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    protected function getDefaultConventionResource($resources): array
    {
        foreach ($resources as $resource) {
            if ($this->checkIfResourceHasConvention($resource) === false) {
                return [$resource];
            }
        }

        return [];
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return bool
     */
    protected function checkIfResourceHasConvention(ResourceInterface $resource): bool
    {
        $reflectionObject = new ReflectionClass($resource);
        $interfaces = $reflectionObject->getInterfaceNames();
        foreach ($interfaces as $interface) {
            $reflectionObject = new ReflectionClass($interface);
            $parentInterfaces = $reflectionObject->getInterfaceNames();
            if (
                $parentInterfaces !== []
                && preg_grep(static::RESOURCE_INTERFACE_SEARCH_PATTERN, $parentInterfaces)
            ) {
                return true;
            }
        }

        return false;
    }
}
