<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector;

use Spryker\Client\CatalogPriceProductConnector\Price\PriceIdentifierBuilder;
use Spryker\Client\Kernel\AbstractFactory;

class CatalogPriceProductConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CatalogPriceProductConnector\Price\PriceIdentifierBuilderInterface
     */
    public function createPriceIdentifierBuilder()
    {
        return new PriceIdentifierBuilder(
            $this->getCurrencyClient(),
            $this->getPriceClient(),
            $this->getPriceProductClient()
        );
    }

    /**
     * @return \Spryker\Client\CatalogPriceProductConnector\Dependency\CatalogPriceProductConnectorToPriceProductClientInterface
     */
    public function getPriceProductClient()
    {
        return $this->getProvidedDependency(CatalogPriceProductConnectorDependencyProvider::CLIENT_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Client\CatalogPriceProductConnector\Dependency\CatalogPriceProductConnectorToPriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient()
    {
        return $this->getProvidedDependency(CatalogPriceProductConnectorDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Client\CatalogPriceProductConnector\Dependency\CatalogPriceProductConnectorToPriceClientInterface
     */
    protected function getPriceClient()
    {
        return $this->getProvidedDependency(CatalogPriceProductConnectorDependencyProvider::CLIENT_PRICE);
    }

    /**
     * @return \Spryker\Client\CatalogPriceProductConnector\Dependency\CatalogPriceProductConnectorToCurrencyClientInterface
     */
    protected function getCurrencyClient()
    {
        return $this->getProvidedDependency(CatalogPriceProductConnectorDependencyProvider::CLIENT_CURRENCY);
    }
}
