<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToKernelAppFacadeBridge;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToPaymentFacadeBridge;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesFacadeBridge;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesFacadeInterface;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesPaymentFacadeBridge;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Service\SalesPaymentMerchantToUtilEncodingServiceBridge;
use Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface;

/**
 * @method \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig getConfig()
 */
class SalesPaymentMerchantDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_KERNEL_APP = 'SALES_PAYMENT_MERCHANT:FACADE_KERNEL_APP';

    /**
     * @var string
     */
    public const FACADE_SALES_PAYMENT = 'SALES_PAYMENT_MERCHANT:FACADE_SALES_PAYMENT';

    /**
     * @var string
     */
    public const FACADE_PAYMENT = 'SALES_PAYMENT_MERCHANT:FACADE_PAYMENT';

    /**
     * @var string
     */
    public const FACADE_SALES = 'SALES_PAYMENT_MERCHANT:FACADE_SALES';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SALES_PAYMENT_MERCHANT:SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const PLUGIN_MERCHANT_PAYOUT_AMOUNT_CALCULATOR = 'PLUGIN_MERCHANT_PAYOUT_AMOUNT_CALCULATOR';

    /**
     * @var string
     */
    public const PLUGIN_MERCHANT_PAYOUT_REVERSE_AMOUNT_CALCULATOR = 'PLUGIN_MERCHANT_PAYOUT_REVERSE_AMOUNT_CALCULATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addAppKernelFacade($container);
        $container = $this->addPaymentFacade($container);
        $container = $this->addSalesPaymentFacade($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addMerchantPayoutAmountCalculatorPlugin($container);
        $container = $this->addMerchantPayoutReverseAmountCalculatorPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addSalesFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAppKernelFacade(Container $container): Container
    {
        $container->set(static::FACADE_KERNEL_APP, function (Container $container) {
            return new SalesPaymentMerchantToKernelAppFacadeBridge($container->getLocator()->kernelApp()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentFacade(Container $container): Container
    {
        $container->set(static::FACADE_PAYMENT, function (Container $container) {
            return new SalesPaymentMerchantToPaymentFacadeBridge($container->getLocator()->payment()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesPaymentFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES_PAYMENT, function (Container $container) {
            return new SalesPaymentMerchantToSalesPaymentFacadeBridge($container->getLocator()->salesPayment()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container): SalesPaymentMerchantToSalesFacadeInterface {
            return new SalesPaymentMerchantToSalesFacadeBridge($container->getLocator()->sales()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new SalesPaymentMerchantToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantPayoutAmountCalculatorPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_MERCHANT_PAYOUT_AMOUNT_CALCULATOR, function (Container $container) {
            return $this->getMerchantPayoutAmountCalculatorPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantPayoutReverseAmountCalculatorPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_MERCHANT_PAYOUT_REVERSE_AMOUNT_CALCULATOR, function (Container $container) {
            return $this->getMerchantPayoutReverseAmountCalculatorPlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface|null
     */
    protected function getMerchantPayoutAmountCalculatorPlugin(): ?MerchantPayoutCalculatorPluginInterface
    {
        return null;
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface|null
     */
    protected function getMerchantPayoutReverseAmountCalculatorPlugin(): ?MerchantPayoutCalculatorPluginInterface
    {
        return null;
    }
}
