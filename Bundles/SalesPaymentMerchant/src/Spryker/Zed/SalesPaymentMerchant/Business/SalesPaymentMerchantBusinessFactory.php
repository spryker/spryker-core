<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesPaymentMerchant\Business\Expander\PaymentTransmissionItemExpander;
use Spryker\Zed\SalesPaymentMerchant\Business\Expander\PaymentTransmissionItemExpanderInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Calculator\MerchantPayoutAmountCalculatorFallback;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Calculator\MerchantPayoutCalculator;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Calculator\MerchantPayoutCalculatorInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Calculator\MerchantPayoutReverseAmountCalculatorFallback;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Payout\Checker\PaymentMethodPayoutChecker;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Payout\Checker\PaymentMethodPayoutCheckerInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Payout\MerchantPayout;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Payout\MerchantPayoutInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Refund\Checker\PaymentMethodPayoutReverseChecker;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Refund\Checker\PaymentMethodPayoutReverseCheckerInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Refund\MerchantPayoutReverse;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Refund\MerchantPayoutReverseInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\OrderExpenseReader;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\OrderExpenseReaderInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\OrderRefundExpenseReader;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\PaymentMethodReader;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\PaymentMethodReaderInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReader;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReaderInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReversalReader;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReversalReaderInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\TransferEndpointReader;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\TransferEndpointReaderInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Sender\TransferRequestSender;
use Spryker\Zed\SalesPaymentMerchant\Business\Sender\TransferRequestSenderInterface;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToKernelAppFacadeInterface;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToPaymentFacadeInterface;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesFacadeInterface;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesPaymentFacadeInterface;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Service\SalesPaymentMerchantToUtilEncodingServiceInterface;
use Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantDependencyProvider;
use Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface;

/**
 * @method \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig getConfig()
 * @method \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantRepositoryInterface getRepository()
 */
class SalesPaymentMerchantBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Payout\MerchantPayoutInterface
     */
    public function createMerchantPayout(): MerchantPayoutInterface
    {
        return new MerchantPayout(
            $this->createMerchantPayoutAmountCalculator(),
            $this->createTransferEndpointReader(),
            $this->createTransferRequestSender(),
            $this->getEntityManager(),
            $this->createPaymentTransmissionItemExpander(),
            $this->createOrderExpenseReader(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Refund\MerchantPayoutReverseInterface
     */
    public function createMerchantPaymentReverse(): MerchantPayoutReverseInterface
    {
        return new MerchantPayoutReverse(
            $this->createMerchantPayoutReverseAmountCalculator(),
            $this->createTransferEndpointReader(),
            $this->createTransferRequestSender(),
            $this->getEntityManager(),
            $this->createPaymentTransmissionItemExpander(),
            $this->createOrderRefundExpenseReader(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Calculator\MerchantPayoutCalculatorInterface
     */
    public function createMerchantPayoutAmountCalculator(): MerchantPayoutCalculatorInterface
    {
        return new MerchantPayoutCalculator(
            $this->createMerchantPayoutAmountCalculatorFallback(),
            $this->getMerchantPayoutAmountCalculatorPlugin(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Calculator\MerchantPayoutCalculatorInterface
     */
    public function createMerchantPayoutReverseAmountCalculator(): MerchantPayoutCalculatorInterface
    {
        return new MerchantPayoutCalculator(
            $this->createMerchantPayoutReverseAmountCalculatorFallback(),
            $this->getMerchantPayoutReverseAmountCalculatorPlugin(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface
     */
    public function createMerchantPayoutAmountCalculatorFallback(): MerchantPayoutCalculatorPluginInterface
    {
        return new MerchantPayoutAmountCalculatorFallback();
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface
     */
    public function createMerchantPayoutReverseAmountCalculatorFallback(): MerchantPayoutCalculatorPluginInterface
    {
        return new MerchantPayoutReverseAmountCalculatorFallback();
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Business\Reader\PaymentMethodReaderInterface
     */
    public function createPaymentMethodReader(): PaymentMethodReaderInterface
    {
        return new PaymentMethodReader(
            $this->getPaymentFacade(),
            $this->getSalesPaymentFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Business\Reader\TransferEndpointReaderInterface
     */
    public function createTransferEndpointReader(): TransferEndpointReaderInterface
    {
        return new TransferEndpointReader($this->createPaymentMethodReader());
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Business\Sender\TransferRequestSenderInterface
     */
    public function createTransferRequestSender(): TransferRequestSenderInterface
    {
        return new TransferRequestSender(
            $this->getKernelAppFacade(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Business\Expander\PaymentTransmissionItemExpanderInterface
     */
    public function createPaymentTransmissionItemExpander(): PaymentTransmissionItemExpanderInterface
    {
        return new PaymentTransmissionItemExpander(
            $this->createSalesPaymentMerchantPayoutReader(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReaderInterface
     */
    public function createSalesPaymentMerchantPayoutReader(): SalesPaymentMerchantPayoutReaderInterface
    {
        return new SalesPaymentMerchantPayoutReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReversalReaderInterface
     */
    public function createSalesPaymentMerchantPayoutReversalReader(): SalesPaymentMerchantPayoutReversalReaderInterface
    {
        return new SalesPaymentMerchantPayoutReversalReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Payout\Checker\PaymentMethodPayoutCheckerInterface
     */
    public function createPaymentMethodPayoutChecker(): PaymentMethodPayoutCheckerInterface
    {
        return new PaymentMethodPayoutChecker(
            $this->createTransferEndpointReader(),
            $this->createSalesPaymentMerchantPayoutReader(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Refund\Checker\PaymentMethodPayoutReverseCheckerInterface
     */
    public function createPaymentMethodPayoutReverseChecker(): PaymentMethodPayoutReverseCheckerInterface
    {
        return new PaymentMethodPayoutReverseChecker(
            $this->createTransferEndpointReader(),
            $this->createSalesPaymentMerchantPayoutReversalReader(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Business\Reader\OrderExpenseReaderInterface
     */
    public function createOrderExpenseReader(): OrderExpenseReaderInterface
    {
        return new OrderExpenseReader($this->getConfig(), $this->createSalesPaymentMerchantPayoutReader());
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Business\Reader\OrderExpenseReaderInterface
     */
    public function createOrderRefundExpenseReader(): OrderExpenseReaderInterface
    {
        return new OrderRefundExpenseReader($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToKernelAppFacadeInterface
     */
    public function getKernelAppFacade(): SalesPaymentMerchantToKernelAppFacadeInterface
    {
        return $this->getProvidedDependency(SalesPaymentMerchantDependencyProvider::FACADE_KERNEL_APP);
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToPaymentFacadeInterface
     */
    public function getPaymentFacade(): SalesPaymentMerchantToPaymentFacadeInterface
    {
        return $this->getProvidedDependency(SalesPaymentMerchantDependencyProvider::FACADE_PAYMENT);
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesPaymentFacadeInterface
     */
    public function getSalesPaymentFacade(): SalesPaymentMerchantToSalesPaymentFacadeInterface
    {
        return $this->getProvidedDependency(SalesPaymentMerchantDependencyProvider::FACADE_SALES_PAYMENT);
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesPaymentMerchantToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesPaymentMerchantDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Dependency\Service\SalesPaymentMerchantToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): SalesPaymentMerchantToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(SalesPaymentMerchantDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface|null
     */
    public function getMerchantPayoutAmountCalculatorPlugin(): ?MerchantPayoutCalculatorPluginInterface
    {
        return $this->getProvidedDependency(SalesPaymentMerchantDependencyProvider::PLUGIN_MERCHANT_PAYOUT_AMOUNT_CALCULATOR);
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface|null
     */
    public function getMerchantPayoutReverseAmountCalculatorPlugin(): ?MerchantPayoutCalculatorPluginInterface
    {
        return $this->getProvidedDependency(SalesPaymentMerchantDependencyProvider::PLUGIN_MERCHANT_PAYOUT_REVERSE_AMOUNT_CALCULATOR);
    }
}
