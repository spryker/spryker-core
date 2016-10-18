<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\Product;

use Spryker\Yves\Product\Builder\AttributeVariantBuilder;
use Spryker\Yves\Product\Builder\ImageSetBuilder;
use Spryker\Yves\Product\Builder\StorageProductBuilder;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\Product\ProductClientInterface getClient()
 */
class ProductFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\Product\Builder\StorageProductBuilder
     */
    protected function createStorageProductBuilder()
    {
        return new StorageProductBuilder(
            $this->createAttributeVariantBuilder(),
            $this->createImageSetBuilder()
        );
    }

    /**
     * @return \Spryker\Yves\Product\Builder\AttributeVariantBuilder
     */
    protected function createAttributeVariantBuilder()
    {
        return new AttributeVariantBuilder(
            $this->getClient(),
            $this->createImageSetBuilder()
        );
    }

    /**
     * @return \Spryker\Yves\Product\Builder\ImageSetBuilder
     */
    protected function createImageSetBuilder()
    {
        return new ImageSetBuilder();
    }

}
