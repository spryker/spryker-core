<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentAppShipment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PaymentAppShipment\Business\Assigner\ShipmentAssigner;
use Spryker\Zed\PaymentAppShipment\Business\Assigner\ShipmentAssignerInterface;
use Spryker\Zed\PaymentAppShipment\Business\RequestExecutor\ExpressCheckoutPaymentRequestExecutor;
use Spryker\Zed\PaymentAppShipment\Business\RequestExecutor\ExpressCheckoutPaymentRequestExecutorInterface;
use Spryker\Zed\PaymentAppShipment\Dependency\Facade\PaymentAppShipmentToShipmentFacadeInterface;
use Spryker\Zed\PaymentAppShipment\PaymentAppShipmentDependencyProvider;

/**
 * @method \Spryker\Zed\PaymentAppShipment\PaymentAppShipmentConfig getConfig()
 */
class PaymentAppShipmentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PaymentAppShipment\Business\RequestExecutor\ExpressCheckoutPaymentRequestExecutorInterface
     */
    public function createExpressCheckoutPaymentRequestExecutor(): ExpressCheckoutPaymentRequestExecutorInterface
    {
        return new ExpressCheckoutPaymentRequestExecutor(
            $this->getConfig(),
            $this->getShipmentFacade(),
            $this->createShipmentAssigner(),
        );
    }

    /**
     * @return \Spryker\Zed\PaymentAppShipment\Business\Assigner\ShipmentAssignerInterface
     */
    public function createShipmentAssigner(): ShipmentAssignerInterface
    {
        return new ShipmentAssigner($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\PaymentAppShipment\Dependency\Facade\PaymentAppShipmentToShipmentFacadeInterface
     */
    public function getShipmentFacade(): PaymentAppShipmentToShipmentFacadeInterface
    {
        return $this->getProvidedDependency(PaymentAppShipmentDependencyProvider::FACADE_SHIPMENT);
    }
}
