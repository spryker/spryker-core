<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\Product;

use Spryker\Yves\Product\Mapper\AttributeVariantMapper;
use Spryker\Yves\Product\Mapper\StorageProductMapper;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\Product\ProductClientInterface getClient()
 */
class ProductFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\Product\Mapper\StorageProductMapper
     */
    protected function createStorageProductMapper()
    {
        return new StorageProductMapper($this->createAttributeVariantMapper());
    }

    /**
     * @return \Spryker\Yves\Product\Mapper\AttributeVariantMapper
     */
    protected function createAttributeVariantMapper()
    {
        return new AttributeVariantMapper($this->getClient());
    }

}
