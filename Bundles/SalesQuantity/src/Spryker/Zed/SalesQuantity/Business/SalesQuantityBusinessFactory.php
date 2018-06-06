<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesQuantity\Business\Model\Cart\Expander\ProductExpander;
use Spryker\Zed\SalesQuantity\Business\Model\Order\OrderItemTransformer;
use Spryker\Zed\SalesQuantity\Business\Model\Order\OrderItemTransformerInterface;
use Spryker\Zed\SalesQuantity\SalesQuantityDependencyProvider;

/**
 * @method \Spryker\Zed\SalesQuantity\SalesQuantityConfig getConfig()
 */
class SalesQuantityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesQuantity\Business\Model\Order\OrderItemTransformerInterface
     */
    public function createOrderItemTransformer(): OrderItemTransformerInterface
    {
        return new OrderItemTransformer();
    }

    /**
     * @return \Spryker\Zed\SalesQuantity\Business\Model\Cart\Expander\ProductExpanderInterface
     */
    public function createProductExpander()
    {
        return new ProductExpander(
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SalesQuantity\Dependency\Facade\SalesQuantityToProductInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(SalesQuantityDependencyProvider::FACADE_PRODUCT);
    }
}
