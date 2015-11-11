<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Converter\ConverterInterface;
use SprykerFeature\Zed\Payolution\Business\Order\SaverInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\Handler\Transaction\TransactionInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\Handler\Calculation\CalculationInterface;
use SprykerFeature\Zed\Payolution\Business\Log\TransactionStatusLogInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\Method\ApiConstants;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use Generated\Zed\Ide\FactoryAutoCompletion\PayolutionBusiness;

/**
 * @method PayolutionBusiness getFactory()
 * @method PayolutionQueryContainerInterface getQueryContainer()
 * @method PayolutionConfig getConfig()
 */
class PayolutionDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return TransactionInterface
     */
    public function createPaymentTransactionHandler()
    {
        $paymentTransactionHandler = $this->getFactory()->createPaymentHandlerTransactionTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl(), ApiConstants::TRANSACTION_REQUEST_CONTENT_TYPE),
            $this->createConverter(),
            $this->getQueryContainer(),
            $this->getConfig()
        );

        $paymentTransactionHandler->registerMethodMapper(
            $this->getFactory()->createPaymentMethodinvoiceInvoice($this->getConfig())
        );
        $paymentTransactionHandler->registerMethodMapper(
            $this->getFactory()->createPaymentMethodinstallmentInstallment($this->getConfig())
        );

        return $paymentTransactionHandler;
    }

    /**
     * @return CalculationInterface
     */
    public function createPaymentCalculationHandler()
    {
        $paymentCalculationHandler = $this->getFactory()->createPaymentHandlerCalculationCalculation(
            $this->createAdapter($this->getConfig()->getCalculationGatewayUrl(), ApiConstants::CALCULATION_REQUEST_CONTENT_TYPE),
            $this->createConverter(),
            $this->getConfig()
        );

        $paymentCalculationHandler->registerMethodMapper(
            $this->getFactory()->createPaymentMethodinstallmentInstallment($this->getConfig())
        );

        return $paymentCalculationHandler;
    }

    /**
     * @param string $gatewayUrl
     * @param string $contentType
     *
     * @return AdapterInterface
     */
    protected function createAdapter($gatewayUrl, $contentType)
    {
        return $this->getFactory()->createApiAdapterHttpGuzzle($gatewayUrl, $contentType);
    }

    /**
     * @return SaverInterface
     */
    public function createOrderSaver()
    {
        return $this->getFactory()->createOrderSaver();
    }

    /**
     * @return ConverterInterface
     */
    public function createConverter()
    {
        return $this->getFactory()->createApiConverterConverter();
    }

    /**
     * @return TransactionStatusLogInterface
     */
    public function createTransactionStatusLog()
    {
        return $this->getFactory()->createLogTransactionStatusLog($this->getQueryContainer());
    }

}
