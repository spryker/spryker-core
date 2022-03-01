<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\GlueApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\Exception\MissingApiConventionException;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceFilterPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class ConventionResourceFilterPlugin extends AbstractPlugin implements ResourceFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters the provided resources by the convention `GlueRequestTransfer.convention` has.
     * - Does nothing if `GlueRequestTransfer.convention` is empty.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resources
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    public function filter(GlueRequestTransfer $glueRequestTransfer, array $resources): array
    {
        if (!$glueRequestTransfer->getConvention()) {
            return $resources;
        }

        $apiConventionPlugin = $this->getApiConventionPluginClassName($glueRequestTransfer->getConvention());
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
     * @throws \Spryker\Glue\GlueApplication\Exception\MissingApiConventionException
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface
     */
    protected function getApiConventionPluginClassName(string $conventionName): ApiConventionPluginInterface
    {
        foreach ($this->getFactory()->getApiConventionPlugins() as $apiConventionPlugin) {
            if ($apiConventionPlugin->getName() === $conventionName) {
                return $apiConventionPlugin;
            }
        }

        throw new MissingApiConventionException(
            sprintf(
                'No plugin that implements `%s` was found for the current request.
                Please implement one and inject into `GlueApplicationDependencyProvider::getApiConventionPlugins()`',
                ApiConventionPluginInterface::class,
            ),
        );
    }
}
