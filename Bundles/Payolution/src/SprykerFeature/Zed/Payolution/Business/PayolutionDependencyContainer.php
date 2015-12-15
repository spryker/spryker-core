<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Business;

use Spryker\Zed\Payolution\Business\Log\TransactionStatusLog;
use Spryker\Zed\Payolution\Business\Api\Converter\Converter;
use Spryker\Zed\Payolution\Business\Order\Saver;
use Spryker\Zed\Payolution\Business\Api\Adapter\Http\Guzzle;
use Spryker\Zed\Payolution\Business\Payment\Handler\Calculation\Calculation;
use Spryker\Zed\Payolution\Business\Payment\Method\Installment\Installment;
use Spryker\Zed\Payolution\Business\Payment\Method\Invoice\Invoice;
use Spryker\Zed\Payolution\Business\Payment\Handler\Transaction\Transaction;
use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Payolution\Business\Api\Converter\ConverterInterface;
use Spryker\Zed\Payolution\Business\Order\SaverInterface;
use Spryker\Zed\Payolution\Business\Payment\Handler\Transaction\TransactionInterface;
use Spryker\Zed\Payolution\Business\Payment\Handler\Calculation\CalculationInterface;
use Spryker\Zed\Payolution\Business\Log\TransactionStatusLogInterface;
use Spryker\Zed\Payolution\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;
use Spryker\Zed\Payolution\PayolutionConfig;

/**
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
            $this->createInvoice()
        );
        $paymentTransactionHandler->registerMethodMapper(
            $this->createInstallment()
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
            $this->createInstallment()
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

    /**
     * @return Invoice
     */
    protected function createInvoice()
    {
        return new Invoice($this->getConfig());
    }

    /**
     * @return Installment
     */
    protected function createInstallment()
    {
        return new Installment($this->getConfig());
    }

}
