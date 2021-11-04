<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOption;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductOption\KeyBuilder\ProductOptionKeyBuilder;
use Spryker\Client\ProductOption\OptionGroup\ProductOptionValuePriceReader;
use Spryker\Client\ProductOption\Storage\ProductOptionStorage;

class ProductOptionFactory extends AbstractFactory
{
    /**
     * @param string $localeName
     *
     * @return \Spryker\Client\ProductOption\Storage\ProductOptionStorageInterface
     */
    public function createProductOptionStorage($localeName)
    {
        return new ProductOptionStorage(
            $this->getStorageClient(),
            $this->createKeyBuilder(),
            $this->createProductOptionValuePriceReader(),
            $localeName,
        );
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createKeyBuilder()
    {
        return new ProductOptionKeyBuilder();
    }

    /**
     * @return \Spryker\Client\ProductOption\Dependency\Client\ProductOptionToStorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductOption\OptionGroup\ProductOptionValuePriceReaderInterface
     */
    protected function createProductOptionValuePriceReader()
    {
        return new ProductOptionValuePriceReader(
            $this->getPriceClient(),
            $this->getCurrencyClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOption\Dependency\Client\ProductOptionToPriceClientInterface
     */
    protected function getPriceClient()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::CLIENT_PRICE);
    }

    /**
     * @return \Spryker\Client\ProductOption\Dependency\Client\ProductOptionToCurrencyClientInterface
     */
    protected function getCurrencyClient()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::CLIENT_CURRENCY);
    }
}
