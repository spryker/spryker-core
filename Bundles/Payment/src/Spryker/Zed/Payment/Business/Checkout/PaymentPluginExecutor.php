<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Payment\Business\Order\SalesPaymentSaverInterface;
use Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollectionInterface;
use Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPreCheckPluginInterface;
use Spryker\Zed\Payment\PaymentDependencyProvider;

class PaymentPluginExecutor implements PaymentPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollectionInterface
     */
    protected $checkoutPlugins;

    /**
     * @var \Spryker\Zed\Payment\Business\Order\SalesPaymentSaverInterface
     */
    protected $salesPaymentSaver;

    /**
     * @param \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollectionInterface $checkoutPlugins
     * @param \Spryker\Zed\Payment\Business\Order\SalesPaymentSaverInterface $salesPaymentSaver
     */
    public function __construct(
        CheckoutPluginCollectionInterface $checkoutPlugins,
        SalesPaymentSaverInterface $salesPaymentSaver
    ) {
        $this->checkoutPlugins = $checkoutPlugins;
        $this->salesPaymentSaver = $salesPaymentSaver;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function executePreCheckPlugin(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        return $this->executePreConditionPluginsForPayment(
            $quoteTransfer,
            $checkoutResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function executeOrderSaverPlugin(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->savePaymentPriceToPay($quoteTransfer, $checkoutResponseTransfer);

        $this->executePluginsForType(
            PaymentDependencyProvider::CHECKOUT_ORDER_SAVER_PLUGINS,
            $quoteTransfer,
            $checkoutResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function executePostCheckPlugin(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->executePluginsForType(
            PaymentDependencyProvider::CHECKOUT_POST_SAVE_PLUGINS,
            $quoteTransfer,
            $checkoutResponseTransfer
        );
    }

    /**
     * @param string $pluginType
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function executePluginsForType(
        $pluginType,
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $paymentProvider = $quoteTransfer->getPayment()->getPaymentProvider();

        if ($this->hasPlugin($pluginType, $paymentProvider)) {
            $plugin = $this->findPlugin($pluginType, $paymentProvider);
            $plugin->execute($quoteTransfer, $checkoutResponseTransfer);
        }

        $this->executeForCollection($quoteTransfer, $pluginType, $checkoutResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $pluginType
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function executeForCollection(
        QuoteTransfer $quoteTransfer,
        $pluginType,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {

        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if (!$this->hasPlugin($pluginType, $paymentTransfer->getPaymentProvider())) {
                 continue;
            }
            $plugin = $this->findPlugin($pluginType, $paymentTransfer->getPaymentProvider());
            $plugin->execute($quoteTransfer, $checkoutResponseTransfer);
        }
    }

    /**
     * @deprecated Use executePreConditionPluginsForPayments() instead. Will be removed along with QuoteTransfer::getPayment().
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    protected function executePreConditionPluginsForPayment(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $isPassed = true;
        $pluginType = PaymentDependencyProvider::CHECKOUT_PRE_CHECK_PLUGINS;
        $paymentProvider = $quoteTransfer->getPayment()->getPaymentProvider();

        if ($this->hasPlugin($pluginType, $paymentProvider)) {
            $plugin = $this->findPlugin($pluginType, $paymentProvider);
            $isPassed &= $this->executePreConditionPlugin($quoteTransfer, $checkoutResponseTransfer, $plugin);
        }

        $isPassed &= $this->executePreConditionPluginsForPayments($quoteTransfer, $checkoutResponseTransfer);

        return (bool)$isPassed;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    protected function executePreConditionPluginsForPayments(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $isPassed = true;
        $pluginType = PaymentDependencyProvider::CHECKOUT_PRE_CHECK_PLUGINS;

        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if (!$this->hasPlugin($pluginType, $paymentTransfer->getPaymentProvider())) {
                continue;
            }

            $plugin = $this->findPlugin($pluginType, $paymentTransfer->getPaymentProvider());
            $isPassed &= $this->executePreConditionPlugin($quoteTransfer, $checkoutResponseTransfer, $plugin);
        }

        return (bool)$isPassed;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginInterface|\Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPreConditionPluginInterface $plugin
     *
     * @return bool
     */
    protected function executePreConditionPlugin(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer, $plugin)
    {
        if ($plugin instanceof CheckoutPreCheckPluginInterface) {
            return $this->executeCheckoutPreCheckPlugin($quoteTransfer, $checkoutResponseTransfer, $plugin);
        }

        return $plugin->execute($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @deprecated Use executePreConditionPlugin() instead. Will be removed along with \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPreCheckPluginInterface.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPreCheckPluginInterface $plugin
     *
     * @return bool
     */
    protected function executeCheckoutPreCheckPlugin(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer,
        CheckoutPreCheckPluginInterface $plugin
    ) {
        $errorCount = $checkoutResponseTransfer->getErrors()->count();
        $plugin->execute($quoteTransfer, $checkoutResponseTransfer);

        return $errorCount === $checkoutResponseTransfer->getErrors()->count();
    }

    /**
     * @param string $pluginType
     * @param string $provider
     *
     * @return bool
     */
    protected function hasPlugin($pluginType, $provider)
    {
        return $this->checkoutPlugins->has($provider, $pluginType);
    }

    /**
     * @param string $pluginType
     * @param string $provider
     *
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginInterface|\Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPreConditionPluginInterface
     */
    protected function findPlugin($pluginType, $provider)
    {
        return $this->checkoutPlugins->get($provider, $pluginType);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function savePaymentPriceToPay(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->salesPaymentSaver->saveOrderPayments($quoteTransfer, $checkoutResponseTransfer);
    }
}
