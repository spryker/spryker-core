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
use Spryker\Service\PriceProduct\Model\PriceProductMerger;
use Spryker\Service\PriceProduct\Model\PriceProductMergerInterface;

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
     * @return \Spryker\Service\PriceProduct\FilterStrategy\SinglePriceProductFilterStrategyInterface
     */
    public function createSinglePriceProductFilterStrategy(): SinglePriceProductFilterStrategyInterface
    {
        return new SinglePriceProductFilterMinStrategy();
    }

    /**
     * @return \Spryker\Service\PriceProduct\Model\PriceProductMergerInterface
     */
    public function createPriceProductMerger(): PriceProductMergerInterface
    {
        return new PriceProductMerger();
    }

    /**
     * @return \Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface[]
     */
    public function getPriceProductDecisionPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_PRODUCT_DECISION);
    }
}
