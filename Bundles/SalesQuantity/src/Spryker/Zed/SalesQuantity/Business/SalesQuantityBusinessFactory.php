<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesQuantity\Business\Cart\Expander\ItemExpander;
use Spryker\Zed\SalesQuantity\Business\Discount\DiscountableItem\DiscountableItemTransformer;
use Spryker\Zed\SalesQuantity\Business\Discount\DiscountableItem\DiscountableItemTransformerInterface;
use Spryker\Zed\SalesQuantity\Business\Order\Item\ItemQuantityValidator;
use Spryker\Zed\SalesQuantity\Business\Order\Item\ItemTransformer;
use Spryker\Zed\SalesQuantity\Business\Order\Item\ItemTransformerInterface;
use Spryker\Zed\SalesQuantity\Dependency\Service\SalesQuantityToUtilPriceServiceInterface;
use Spryker\Zed\SalesQuantity\SalesQuantityDependencyProvider;

/**
 * @method \Spryker\Zed\SalesQuantity\SalesQuantityConfig getConfig()
 * @method \Spryker\Zed\SalesQuantity\Persistence\SalesQuantityRepositoryInterface getRepository()
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
            $this->getRepository()
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\SalesQuantity\Dependency\Facade\SalesQuantityToProductFacadeInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(SalesQuantityDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\SalesQuantity\Business\Discount\DiscountableItem\DiscountableItemTransformerInterface
     */
    public function createDiscountableItemTransformer(): DiscountableItemTransformerInterface
    {
        return new DiscountableItemTransformer($this->getUtilPriceService());
    }

    /**
     * @return \Spryker\Zed\SalesQuantity\Business\Order\Item\ItemQuantityValidatorInterface
     */
    public function createItemQuantityValidator()
    {
        return new ItemQuantityValidator($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\SalesQuantity\Dependency\Service\SalesQuantityToUtilPriceServiceInterface
     */
    public function getUtilPriceService(): SalesQuantityToUtilPriceServiceInterface
    {
        return $this->getProvidedDependency(SalesQuantityDependencyProvider::SERVICE_UTIL_PRICE);
    }
}
