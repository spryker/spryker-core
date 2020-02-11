<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApprovalShipmentConnector;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuoteApprovalShipmentConnector\Checker\QuoteShipmentChecker;
use Spryker\Client\QuoteApprovalShipmentConnector\Checker\QuoteShipmentCheckerInterface;
use Spryker\Client\QuoteApprovalShipmentConnector\Dependency\Service\QuoteApprovalShipmentConnectorToShipmentServiceInterface;

class QuoteApprovalShipmentConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\QuoteApprovalShipmentConnector\Checker\QuoteShipmentCheckerInterface
     */
    public function createQuoteShipmentChecker(): QuoteShipmentCheckerInterface
    {
        return new QuoteShipmentChecker(
            $this->getShipmentService()
        );
    }

    /**
     * @return \Spryker\Client\QuoteApprovalShipmentConnector\Dependency\Service\QuoteApprovalShipmentConnectorToShipmentServiceInterface
     */
    public function getShipmentService(): QuoteApprovalShipmentConnectorToShipmentServiceInterface
    {
        return $this->getProvidedDependency(QuoteApprovalShipmentConnectorDependencyProvider::SERVICE_SHIPMENT);
    }
}
