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
     * @see \Spryker\Zed\Payment\PaymentDependencyProvider for plugin types
     *
     * @example
     * [
     *  'pluginType1' => [
     *      'paymentMethod1' => true
     *  ]
     * ]
     *
     * @var array
     */
    protected $executedProviderPlugins = [];

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
        return $this->executePreCheckPluginsForPayment(
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

        if ($this->hasPlugin($pluginType, $paymentProvider) && !$this->isAlreadyExecuted($pluginType, $paymentProvider)) {
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
            if (!$this->hasPlugin($pluginType, $paymentTransfer->getPaymentProvider()) || $this->isAlreadyExecuted($pluginType, $paymentTransfer->getPaymentProvider())) {
                continue;
            }
            $plugin = $this->findPlugin($pluginType, $paymentTransfer->getPaymentProvider());
            $plugin->execute($quoteTransfer, $checkoutResponseTransfer);
            $this->executedProviderPlugins[$pluginType][$paymentTransfer->getPaymentProvider()] = true;
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
    protected function executePreCheckPluginsForPayment(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $isPassed = true;
        $pluginType = PaymentDependencyProvider::CHECKOUT_PRE_CHECK_PLUGINS;
        $paymentProvider = $quoteTransfer->getPayment()->getPaymentProvider();

        if ($this->hasPlugin($pluginType, $paymentProvider)) {
            $plugin = $this->findPlugin($pluginType, $paymentProvider);
            $isPassed &= $this->executePreCheckPluginPayment($quoteTransfer, $checkoutResponseTransfer, $plugin);
        }

        return $isPassed && $this->executePreCheckPluginsForPayments($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    protected function executePreCheckPluginsForPayments(
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
            $isPassed &= $this->executePreCheckPluginPayment($quoteTransfer, $checkoutResponseTransfer, $plugin);
        }

        return (bool)$isPassed;
    }

    /**
     * @deprecated Use executePreCheckPluginPaymentPlugin() instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPreCheckPluginInterface $plugin
     *
     * @return bool
     */
    protected function executePreCheckPluginPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer, CheckoutPreCheckPluginInterface $plugin)
    {
        $errorCount = $checkoutResponseTransfer->getErrors()->count();
        $result = $plugin->execute($quoteTransfer, $checkoutResponseTransfer);
        
        if ($result === null) {
            return $errorCount === $checkoutResponseTransfer->getErrors()->count();
        }
        
        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPreCheckPluginInterface $plugin
     *
     * @return bool
     */
    protected function executePreCheckPluginPaymentPlugin(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer, CheckoutPreCheckPluginInterface $plugin)
    {
        return $plugin->execute($quoteTransfer, $checkoutResponseTransfer);
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
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginInterface|\Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPreCheckPluginInterface
     */
    protected function findPlugin($pluginType, $provider)
    {
        return $this->checkoutPlugins->get($provider, $pluginType);
    }

    /**
     * @param string $pluginType
     * @param string $paymentProvider
     *
     * @return bool
     */
    protected function isAlreadyExecuted($pluginType, $paymentProvider)
    {
        if (isset($this->executedProviderPlugins[$pluginType]) && isset($this->executedProviderPlugins[$pluginType][$paymentProvider])) {
            return true;
        }

        return false;
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
