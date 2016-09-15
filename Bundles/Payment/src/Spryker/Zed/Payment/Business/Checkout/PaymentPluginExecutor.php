<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollectionInterface;
use Spryker\Zed\Payment\PaymentDependencyProvider;

class PaymentPluginExecutor
{

    /**
     * @var \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollectionInterface
     */
    protected $checkoutPlugins;

    /**
     * @param \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollectionInterface $checkoutPlugins
     */
    public function __construct(CheckoutPluginCollectionInterface $checkoutPlugins)
    {
        $this->checkoutPlugins = $checkoutPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function executePreCheckPlugin(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($this->hasPlugin(PaymentDependencyProvider::CHECKOUT_PRE_CHECK_PLUGINS,  $quoteTransfer)) {
            $plugin = $this->findPlugin(PaymentDependencyProvider::CHECKOUT_PRE_CHECK_PLUGINS,  $quoteTransfer);
            $plugin->execute($quoteTransfer, $checkoutResponseTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function executeOrderSaverPlugin(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($this->hasPlugin(PaymentDependencyProvider::CHECKOUT_ORDER_SAVER_PLUGINS, $quoteTransfer)) {
            $plugin = $this->findPlugin(PaymentDependencyProvider::CHECKOUT_ORDER_SAVER_PLUGINS, $quoteTransfer);
            $plugin->execute($quoteTransfer, $checkoutResponseTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function executePostCheckPlugin(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($this->hasPlugin(PaymentDependencyProvider::CHECKOUT_POST_SAVE_PLUGINS, $quoteTransfer)) {
            $plugin = $this->findPlugin(PaymentDependencyProvider::CHECKOUT_POST_SAVE_PLUGINS, $quoteTransfer);
            $plugin->execute($quoteTransfer, $checkoutResponseTransfer);
        }
    }

    /**
     * @param string $pluginType
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasPlugin($pluginType, QuoteTransfer $quoteTransfer)
    {
        $this->assertResolverRequirements($quoteTransfer);

        $provider = $quoteTransfer->getPayment()->getPaymentProvider();

        return $this->checkoutPlugins->has($provider, $pluginType);
    }

    /**
     * @param string $pluginType
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginInterface
     */
    protected function findPlugin($pluginType, QuoteTransfer $quoteTransfer)
    {
        $this->assertResolverRequirements($quoteTransfer);

        $provider = $quoteTransfer->getPayment()->getPaymentProvider();

        return $this->checkoutPlugins->get($provider, $pluginType);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertResolverRequirements(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requirePayment();
        $quoteTransfer->getPayment()
            ->requirePaymentProvider();
    }

}
