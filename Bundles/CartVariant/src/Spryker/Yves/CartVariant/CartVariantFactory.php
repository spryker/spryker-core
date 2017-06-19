<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CartVariant;

use Spryker\Yves\CartVariant\Mapper\CartItemsAttributeMapper;
use Spryker\Yves\CartVariant\Mapper\CartItemsAvailabilityMapper;
use Spryker\Yves\Kernel\AbstractFactory;

class CartVariantFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Availability\AvailabilityClientInterface
     */
    public function getAvailabilityClient()
    {
        return $this->getProvidedDependency(CartVariantDependencyProvider::CLIENT_AVAILABILITY);
    }

    /**
     * @return \Spryker\Client\ProductOption\ProductOptionClientInterface
     */
    public function getProductOptionClient()
    {
        return $this->getProvidedDependency(CartVariantDependencyProvider::CLIENT_PRODUCT_OPTION);
    }

    /**
     * @return \Spryker\Client\Product\ProductClientInterface
     */
    public function getProductClient()
    {
        return $this->getProvidedDependency(CartVariantDependencyProvider::CLIENT_PRODUCT);
    }

    /**
     * @return \Spryker\Yves\CartVariant\Mapper\CartItemsMapperInterface
     */
    public function createCartItemsAttributeMapper()
    {
        return new CartItemsAttributeMapper(
            $this->getProductClient(),
            $this->createCartItemsAvailabilityMapper()
        );
    }

    /**
     * @return \Spryker\Yves\CartVariant\Mapper\CartItemsMapperInterface
     */
    public function createCartItemsAvailabilityMapper()
    {
        return new CartItemsAvailabilityMapper($this->getAvailabilityClient());
    }

}
