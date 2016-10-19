<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Product;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Product\Mapper\AttributeVariantMapper;
use Spryker\Yves\Product\Mapper\StorageProductMapper;

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
