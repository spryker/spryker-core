<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router\ResourceRouter;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

class ConventionResourceFilter implements ConventionResourceFilterInterface
{
    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface>
     */
    protected array $conventionPlugins;

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
            return $resources;
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
}
