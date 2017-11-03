<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector;

use Spryker\Client\Kernel\AbstractFactory;

class CatalogPriceProductConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CatalogPriceProductConnector\Dependency\CatalogPriceProductConnectorToPriceProductInterface
     */
    public function getPriceProductClient()
    {
        return $this->getProvidedDependency(CatalogPriceProductConnectorDependencyProvider::CLIENT_PRICE_PRODUCT);
    }
}
