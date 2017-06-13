<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Cart;

use Spryker\Yves\Cart\Mapper\CartItemsAttributeAndAvailabilityMapper;
use Spryker\Yves\Cart\Mapper\CartItemsAttributeMapper;
use Spryker\Yves\Cart\Mapper\CartItemsAvailabilityMapper;
use Spryker\Yves\Kernel\AbstractFactory;

class CartFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Availability\AvailabilityClientInterface
     */
    public function getAvailabilityClient()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CLIENT_AVAILABILITY);
    }

    /**
     * @return \Spryker\Client\ProductOption\ProductOptionClientInterface
     */
    public function getProductOptionClient()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CLIENT_PRODUCT_OPTION);
    }

    /**
     * @return \Spryker\Client\Product\ProductClientInterface
     */
    public function getProductClient()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CLIENT_PRODUCT);
    }

    /**
     * @return \Spryker\Yves\Cart\Mapper\CartItemsAttributeMapper
     */
    public function createCartItemsAttributeMapper()
    {
        return new CartItemsAttributeMapper(
            $this->getProductClient(),
            $this->createCartItemsAvailabilityMapper()
        );
    }

    /**
     * @return \Spryker\Yves\Cart\Mapper\CartItemsAvailabilityMapper
     */
    public function createCartItemsAvailabilityMapper()
    {
        return new CartItemsAvailabilityMapper($this->getAvailabilityClient());
    }

}
