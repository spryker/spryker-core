<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOption;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductOption\KeyBuilder\ProductOptionKeyBuilder;
use Spryker\Client\ProductOption\Storage\ProductOptionStorage;

class ProductOptionFactory extends AbstractFactory
{

    /**
     * @param string $localeName
     *
     * @return \Spryker\Client\ProductOption\Storage\ProductOptionStorage
     */
    public function createProductOptionStorage($localeName)
    {
        return new ProductOptionStorage(
            $this->getStorage(),
            $this->createKeyBuilder(),
            $localeName
        );
    }

    /**
     * @return \Spryker\Client\ProductOption\KeyBuilder\ProductOptionKeyBuilder
     */
    protected function createKeyBuilder()
    {
        return new ProductOptionKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::KV_STORAGE);
    }

}
