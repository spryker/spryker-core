<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\PriceProduct\Model\PriceDecision\PriceProductMatcher;
use Spryker\Service\PriceProduct\Model\PriceDecision\PriceProductMatcherInterface;

class PriceProductServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\PriceProduct\Model\PriceDecision\PriceProductMatcherInterface
     */
    public function createPriceProductMatcher(): PriceProductMatcherInterface
    {
        return new PriceProductMatcher($this->getPriceProductDecisionPlugins());
    }

    /**
     * @return \Spryker\Service\PriceProduct\Dependency\Plugin\PriceProductDecisionPluginInterface[]
     */
    protected function getPriceProductDecisionPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_PRODUCT_DECISION);
    }
}
