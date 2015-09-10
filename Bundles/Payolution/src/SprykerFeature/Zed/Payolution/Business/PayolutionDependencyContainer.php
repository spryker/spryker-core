<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Generated\Zed\Ide\FactoryAutoCompletion\PayolutionBusiness;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Order\OrderManagerInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\EntityToRequestMapper\OrderToPreAuthorization;
use SprykerFeature\Zed\Payolution\Business\Payment\PaymentManagerInterface;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use SprykerFeature\Zed\Payolution\PayolutionDependencyProvider;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;
use SprykerFeature\Zed\Payone\PayoneDependencyProvider;

/**
 * @method PayolutionBusiness getFactory()
 * @method PayolutionQueryContainerInterface getQueryContainer()
 * @method PayolutionConfig getConfig()
 */
class PayolutionDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return PaymentManagerInterface
     */
    public function createPaymentManager()
    {
        $paymentManager = $this
            ->getFactory()
            ->createPaymentPaymentManager(
                $this->createExecutionAdapter(),
                $this->getQueryContainer()
            );

        $paymentManager->registerMethodMapper(
            $this->getFactory()->createPaymentMethodMapperInvoice($this->getConfig())
        );

        return $paymentManager;
    }

    /**
     * @return AdapterInterface
     */
    protected function createExecutionAdapter()
    {
        $gatewayUrl = $this->getConfig()->getGatewayUrl();
        return $this
            ->getFactory()
            ->createApiAdapterHttpGuzzle($gatewayUrl);
    }

    /**
     * @return OrderManagerInterface
     */
    public function createOrderManager()
    {
        return $this->getFactory()->createOrderOrderManager();
    }

}
