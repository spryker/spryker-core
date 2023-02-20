<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\PluginResolver;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class QueryExpanderPluginResolver extends AbstractDependentPluginResolver implements QueryExpanderPluginResolverInterface
{
    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $query
     * @param array<string, array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>> $queryExpanderPluginVariants
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface> $defaultQueryExpanderPlugins
     *
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    public function resolve(
        QueryInterface $query,
        array $queryExpanderPluginVariants,
        array $defaultQueryExpanderPlugins
    ): array {
        /** @phpstan-var array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface> $applicableQueryExpanderPlugins */
        $applicableQueryExpanderPlugins = $this->resolveByType($query, $queryExpanderPluginVariants);

        if ($applicableQueryExpanderPlugins) {
            return $applicableQueryExpanderPlugins;
        }

        return $defaultQueryExpanderPlugins;
    }
}
