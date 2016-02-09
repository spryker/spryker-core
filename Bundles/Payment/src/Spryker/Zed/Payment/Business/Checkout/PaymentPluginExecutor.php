<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payment\Business\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Payment\PaymentDependencyProvider;
use Spryker\Zed\Payment\Business\Exception\PaymentProviderNotFoundException;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;

class PaymentPluginExecutor
{
    /**
     * @var array
     */
    protected $checkoutPlugins;

    /**
     * @param array $checkoutPlugins
     */
    public function __construct(array $checkoutPlugins)
    {
        $this->checkoutPlugins = $checkoutPlugins;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function executePreCheckPlugin(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $plugin = $this->findPlugin(PaymentDependencyProvider::CHECKOUT_PRE_CHECK_PLUGINS,  $quoteTransfer);
        $plugin->checkCondition($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function executeOrderSaverPlugin(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $plugin = $this->findPlugin(PaymentDependencyProvider::CHECKOUT_ORDER_SAVER_PLUGINS, $quoteTransfer);
        $plugin->saveOrder($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     *
     */
    public function executePostCheckPlugin(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $plugin = $this->findPlugin(PaymentDependencyProvider::CHECKOUT_POST_SAVE_PLUGINS, $quoteTransfer);
        $plugin->executeHook($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @param string $pluginType
     * @param QuoteTransfer $quoteTransfer
     *
     * @throws PaymentProviderNotFoundException
     *
     * @return CheckoutPreConditionInterface|CheckoutSaveOrderInterface|CheckoutPostSaveHookInterface
     */
    protected function findPlugin($pluginType, QuoteTransfer $quoteTransfer)
    {
        $this->assertResolverRequirements($quoteTransfer);

        $paymentProviderName = strtolower($quoteTransfer->getPayment()->getPaymentProvider());
        $plugins = array_change_key_case($this->checkoutPlugins[$pluginType], CASE_LOWER);

        if (array_key_exists($paymentProviderName, $plugins) === false) {
            throw new PaymentProviderNotFoundException(
                sprintf(
                  'Payment provider with name "%s" is not register in checkout "%s" stack. You can add it in "%s".',
                    $paymentProviderName,
                    $pluginType,
                    PaymentDependencyProvider::class
                )
            );
        }

        return $plugins[$paymentProviderName];
    }

    /**
     * @param QuoteTransfer $quoteTransfer
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
