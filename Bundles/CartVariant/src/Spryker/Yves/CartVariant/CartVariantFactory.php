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
     * @return \Spryker\Yves\CartVariant\Dependency\Client\CartVariantToAvailabilityStorageClientBridgeInterface
     */
    public function getAvailabilityStorageClient()
    {
        return $this->getProvidedDependency(CartVariantDependencyProvider::CLIENT_AVAILABILITY_STORAGE);
    }

    /**
     * @return \Spryker\Yves\CartVariant\Dependency\Client\CartVariantToProductClientBridgeInterface
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
        return new CartItemsAvailabilityMapper($this->getAvailabilityStorageClient());
    }
}
