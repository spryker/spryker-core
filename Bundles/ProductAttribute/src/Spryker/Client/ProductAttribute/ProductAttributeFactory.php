<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAttribute;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductAttribute\Dependency\Client\ProductAttributeToZedRequestClientInterface;
use Spryker\Client\ProductAttribute\Zed\ProductAttributeStub;
use Spryker\Client\ProductAttribute\Zed\ProductAttributeStubInterface;

/**
 * @method \Spryker\Client\ProductAttribute\ProductAttributeConfig getConfig()
 */
class ProductAttributeFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductAttribute\Zed\ProductAttributeStubInterface
     */
    public function createProductAttributeStub(): ProductAttributeStubInterface
    {
        return new ProductAttributeStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ProductAttribute\Dependency\Client\ProductAttributeToZedRequestClientInterface
     */
    public function getZedRequestClient(): ProductAttributeToZedRequestClientInterface
    {
        return $this->getProvidedDependency(ProductAttributeDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
