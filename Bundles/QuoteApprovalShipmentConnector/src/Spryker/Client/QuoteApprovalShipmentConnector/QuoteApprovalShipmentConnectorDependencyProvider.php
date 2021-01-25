<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApprovalShipmentConnector;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\QuoteApprovalShipmentConnector\Dependency\Service\QuoteApprovalShipmentConnectorToShipmentServiceBridge;

class QuoteApprovalShipmentConnectorDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_SHIPMENT = 'SERVICE_SHIPMENT';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addShipmentService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addShipmentService(Container $container): Container
    {
        $container->set(static::SERVICE_SHIPMENT, function (Container $container) {
            return new QuoteApprovalShipmentConnectorToShipmentServiceBridge(
                $container->getLocator()->shipment()->service()
            );
        });

        return $container;
    }
}
