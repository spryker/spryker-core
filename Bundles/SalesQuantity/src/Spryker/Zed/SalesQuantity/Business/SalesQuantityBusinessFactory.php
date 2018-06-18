<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesQuantity\Business\Cart\Expander\ItemExpander;
use Spryker\Zed\SalesQuantity\Business\Distributor\DiscountableItem\DiscountableItemTransformer;
use Spryker\Zed\SalesQuantity\Business\Distributor\DiscountableItem\DiscountableItemTransformerInterface;
use Spryker\Zed\SalesQuantity\Business\Order\Item\ItemTransformer;
use Spryker\Zed\SalesQuantity\Business\Order\Item\ItemTransformerInterface;
use Spryker\Zed\SalesQuantity\SalesQuantityDependencyProvider;

/**
 * @method \Spryker\Zed\SalesQuantity\SalesQuantityConfig getConfig()
 */
class SalesQuantityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesQuantity\Business\Order\Item\ItemTransformerInterface
     */
    public function createItemTransformer(): ItemTransformerInterface
    {
        return new ItemTransformer();
    }

    /**
     * @return \Spryker\Zed\SalesQuantity\Business\Cart\Expander\ItemExpanderInterface
     */
    public function createItemExpander()
    {
        return new ItemExpander(
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SalesQuantity\Dependency\Facade\SalesQuantityToProductFacadeInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(SalesQuantityDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\SalesQuantity\Business\Distributor\DiscountableItem\DiscountableItemTransformerInterface
     */
    public function createDiscountableItemTransformer(): DiscountableItemTransformerInterface
    {
        return new DiscountableItemTransformer();
    }
}
