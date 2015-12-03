<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business;

use SprykerFeature\Zed\Payolution\Business\Log\TransactionStatusLog;
use SprykerFeature\Zed\Payolution\Business\Api\Converter\Converter;
use SprykerFeature\Zed\Payolution\Business\Order\Saver;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http\Guzzle;
use SprykerFeature\Zed\Payolution\Business\Payment\Handler\Calculation\Calculation;
use SprykerFeature\Zed\Payolution\Business\Payment\Method\Installment\Installment;
use SprykerFeature\Zed\Payolution\Business\Payment\Method\Invoice\Invoice;
use SprykerFeature\Zed\Payolution\Business\Payment\Handler\Transaction\Transaction;
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
        $paymentTransactionHandler = new Transaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl(), ApiConstants::TRANSACTION_REQUEST_CONTENT_TYPE),
            $this->createConverter(),
            $this->getQueryContainer(),
            $this->getConfig()
        );

        $paymentTransactionHandler->registerMethodMapper(
            new Invoice($this->getConfig())
        );
        $paymentTransactionHandler->registerMethodMapper(
            new Installment($this->getConfig())
        );

        return $paymentTransactionHandler;
    }

    /**
     * @return CalculationInterface
     */
    public function createPaymentCalculationHandler()
    {
        $paymentCalculationHandler = new Calculation(
            $this->createAdapter($this->getConfig()->getCalculationGatewayUrl(), ApiConstants::CALCULATION_REQUEST_CONTENT_TYPE),
            $this->createConverter(),
            $this->getConfig()
        );

        $paymentCalculationHandler->registerMethodMapper(
            new Installment($this->getConfig())
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
        return new Guzzle($gatewayUrl, $contentType);
    }

    /**
     * @return SaverInterface
     */
    public function createOrderSaver()
    {
        return new Saver();
    }

    /**
     * @return ConverterInterface
     */
    public function createConverter()
    {
        return new Converter();
    }

    /**
     * @return TransactionStatusLogInterface
     */
    public function createTransactionStatusLog()
    {
        return new TransactionStatusLog($this->getQueryContainer());
    }

}
