<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApprovalShipmentConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\QuoteApprovalShipmentConnector\Business\QuoteFieldProvider\ShipmentQuoteFieldProvider;
use Spryker\Zed\QuoteApprovalShipmentConnector\Business\QuoteFieldProvider\ShipmentQuoteFieldProviderInterface;
use Spryker\Zed\QuoteApprovalShipmentConnector\Dependency\Facade\QuoteApprovalShipmentConnectorToQuoteApprovalFacadeInterface;
use Spryker\Zed\QuoteApprovalShipmentConnector\QuoteApprovalShipmentConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\QuoteApprovalShipmentConnector\QuoteApprovalShipmentConnectorConfig getConfig()
 */
class QuoteApprovalShipmentConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\QuoteApprovalShipmentConnector\Business\QuoteFieldProvider\ShipmentQuoteFieldProviderInterface
     */
    public function createShipmentQuoteFieldProvider(): ShipmentQuoteFieldProviderInterface
    {
        return new ShipmentQuoteFieldProvider(
            $this->getQuoteApprovalFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApprovalShipmentConnector\Dependency\Facade\QuoteApprovalShipmentConnectorToQuoteApprovalFacadeInterface
     */
    public function getQuoteApprovalFacade(): QuoteApprovalShipmentConnectorToQuoteApprovalFacadeInterface
    {
        return $this->getProvidedDependency(QuoteApprovalShipmentConnectorDependencyProvider::FACADE_QUOTE_APPROVAL);
    }
}
