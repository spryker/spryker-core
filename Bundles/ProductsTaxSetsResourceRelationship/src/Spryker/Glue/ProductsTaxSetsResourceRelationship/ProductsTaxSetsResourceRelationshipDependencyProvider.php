<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsTaxSetsResourceRelationship;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductsTaxSetsResourceRelationship\Dependency\RestResource\ProductsTaxSetsResourceRelationshipToTaxSetsRestApiResourceBridge;

class ProductsTaxSetsResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    public const RESOURCE_TAX_SETS = 'RESOURCE_TAX_SETS';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductTaxSetsResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductTaxSetsResource(Container $container): Container
    {
        $container[static::RESOURCE_TAX_SETS] = function (Container $container) {
            return new ProductsTaxSetsResourceRelationshipToTaxSetsRestApiResourceBridge(
                $container->getLocator()->taxSetsRestApi()->resource()
            );
        };

        return $container;
    }
}
