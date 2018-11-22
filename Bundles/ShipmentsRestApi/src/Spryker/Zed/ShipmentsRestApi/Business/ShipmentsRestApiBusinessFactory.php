<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentsRestApi\Business\Quote\QuoteMapper;
use Spryker\Zed\ShipmentsRestApi\Business\Quote\QuoteMapperInterface;
use Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface;
use Spryker\Zed\ShipmentsRestApi\ShipmentsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentsRestApi\ShipmentsRestApiConfig getConfig()
 */
class ShipmentsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShipmentsRestApi\Business\Quote\QuoteMapperInterface
     */
    public function createQuoteMapper(): QuoteMapperInterface
    {
        return new QuoteMapper($this->getShipmentFacade());
    }

    /**
     * @return \Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface
     */
    public function getShipmentFacade(): ShipmentsRestApiToShipmentFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentsRestApiDependencyProvider::FACADE_SHIPMENT);
    }
}
