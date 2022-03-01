<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\Exception\AmbiguousResourceException;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

class RequestResourcePluginFilter implements RequestResourcePluginFilterInterface
{
    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceFilterPluginInterface>
     */
    protected array $resourceFilterPlugins;

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceFilterPluginInterface> $resourceFilterPlugins
     */
    public function __construct(array $resourceFilterPlugins)
    {
        $this->resourceFilterPlugins = $resourceFilterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resourcePlugins
     *
     * @throws \Spryker\Glue\GlueApplication\Exception\AmbiguousResourceException
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface|null
     */
    public function filterResourcePlugins(GlueRequestTransfer $glueRequestTransfer, array $resourcePlugins): ?ResourceInterface
    {
        if (!$glueRequestTransfer->getResource()) {
            return null;
        }

        $filteredResourcePlugins = $this->filterByResource($resourcePlugins, $glueRequestTransfer);
        foreach ($this->resourceFilterPlugins as $resourceFilterPlugin) {
            $filteredResourcePlugins = $resourceFilterPlugin->filter($glueRequestTransfer, $filteredResourcePlugins);
        }

        if (count($filteredResourcePlugins) > 1) {
            throw new AmbiguousResourceException(sprintf(
                'More than one %s plugin was found to match',
                ResourceInterface::class,
            ));
        }

        return count($filteredResourcePlugins) !== 0 ? current($filteredResourcePlugins) : null;
    }

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resourcePlugins
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    protected function filterByResource(array $resourcePlugins, GlueRequestTransfer $glueRequestTransfer): array
    {
        return array_filter(
            $resourcePlugins,
            function (ResourceInterface $resourcePlugin) use ($glueRequestTransfer): bool {
                return $glueRequestTransfer->getResource()->getResourceName() === $resourcePlugin->getType();
            },
        );
    }
}
