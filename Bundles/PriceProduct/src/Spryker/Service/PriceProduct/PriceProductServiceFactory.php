<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\PriceProduct\FilterStrategy\SinglePriceProductFilterMinStrategy;
use Spryker\Service\PriceProduct\FilterStrategy\SinglePriceProductFilterStrategyInterface;
use Spryker\Service\PriceProduct\Model\PriceProductMatcher;
use Spryker\Service\PriceProduct\Model\PriceProductMatcherInterface;

class PriceProductServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\PriceProduct\Model\PriceProductMatcherInterface
     */
    public function createPriceProductMatcher(): PriceProductMatcherInterface
    {
        return new PriceProductMatcher(
            $this->getPriceProductDecisionPlugins(),
            $this->createSinglePriceProductFilterStrategy()
        );
    }

    /**
     * @return SinglePriceProductFilterStrategyInterface
     */
    public function createSinglePriceProductFilterStrategy(): SinglePriceProductFilterStrategyInterface
    {
        return new SinglePriceProductFilterMinStrategy();
    }

    /**
     * @return \Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface[]
     */
    public function getPriceProductDecisionPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_PRODUCT_DECISION);
    }
}
