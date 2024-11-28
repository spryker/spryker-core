<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentApp\Business\RequestExecutor;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\PaymentApp\Business\Expander\QuotePaymentExpanderInterface;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToCartFacadeInterface;

class ExpressCheckoutPaymentRequestExecutor implements ExpressCheckoutPaymentRequestExecutorInterface
{
    /**
     * @var \Spryker\Zed\PaymentApp\Business\Expander\QuotePaymentExpanderInterface
     */
    protected QuotePaymentExpanderInterface $quotePaymentExpander;

    /**
     * @var \Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToCartFacadeInterface
     */
    protected PaymentAppToCartFacadeInterface $cartFacade;

    /**
     * @var list<\Spryker\Zed\PaymentAppExtension\Dependency\Plugin\ExpressCheckoutPaymentRequestProcessorPluginInterface>
     */
    protected array $expressCheckoutPaymentRequestProcessorPlugins;

    /**
     * @param \Spryker\Zed\PaymentApp\Business\Expander\QuotePaymentExpanderInterface $quotePaymentExpander
     * @param \Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToCartFacadeInterface $cartFacade
     * @param list<\Spryker\Zed\PaymentAppExtension\Dependency\Plugin\ExpressCheckoutPaymentRequestProcessorPluginInterface> $expressCheckoutPaymentRequestProcessorPlugins
     */
    public function __construct(
        QuotePaymentExpanderInterface $quotePaymentExpander,
        PaymentAppToCartFacadeInterface $cartFacade,
        array $expressCheckoutPaymentRequestProcessorPlugins
    ) {
        $this->expressCheckoutPaymentRequestProcessorPlugins = $expressCheckoutPaymentRequestProcessorPlugins;
        $this->cartFacade = $cartFacade;
        $this->quotePaymentExpander = $quotePaymentExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer
     */
    public function processExpressCheckoutPaymentRequest(
        ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
    ): ExpressCheckoutPaymentResponseTransfer {
        $quoteTransfer = $expressCheckoutPaymentRequestTransfer->getQuoteOrFail();
        $quoteTransfer = $this->quotePaymentExpander->expandQuoteWithPayment($quoteTransfer);

        $expressCheckoutPaymentResponseTransfer = $this->executeExpressCheckoutPaymentRequestProcessorPlugins(
            $expressCheckoutPaymentRequestTransfer->setQuote($quoteTransfer),
        );

        if ($expressCheckoutPaymentResponseTransfer->getErrors()->count()) {
            return $expressCheckoutPaymentResponseTransfer;
        }

        $quoteResponseTransfer = $this->cartFacade->reloadItemsInQuote($quoteTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->addQuoteErrors($quoteResponseTransfer, $expressCheckoutPaymentResponseTransfer);
        }

        return $expressCheckoutPaymentResponseTransfer->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer
     */
    protected function executeExpressCheckoutPaymentRequestProcessorPlugins(
        ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
    ): ExpressCheckoutPaymentResponseTransfer {
        $expressCheckoutPaymentResponseTransfer = (new ExpressCheckoutPaymentResponseTransfer())
            ->setQuote($expressCheckoutPaymentRequestTransfer->getQuoteOrFail());

        foreach ($this->expressCheckoutPaymentRequestProcessorPlugins as $expressCheckoutPaymentRequestProcessorPlugin) {
            $expressCheckoutPaymentResponseTransfer = $expressCheckoutPaymentRequestProcessorPlugin->processExpressCheckoutPaymentRequest(
                $expressCheckoutPaymentRequestTransfer,
            );

            if ($expressCheckoutPaymentResponseTransfer->getErrors()->count()) {
                return $expressCheckoutPaymentResponseTransfer;
            }

            $expressCheckoutPaymentRequestTransfer->fromArray($expressCheckoutPaymentResponseTransfer->toArray(), true);
        }

        return $expressCheckoutPaymentResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer $expressCheckoutPaymentResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer
     */
    protected function addQuoteErrors(
        QuoteResponseTransfer $quoteResponseTransfer,
        ExpressCheckoutPaymentResponseTransfer $expressCheckoutPaymentResponseTransfer
    ): ExpressCheckoutPaymentResponseTransfer {
        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $expressCheckoutPaymentResponseTransfer->addError(
                (new ErrorTransfer())->fromArray($quoteErrorTransfer->toArray(), true),
            );
        }

        return $expressCheckoutPaymentResponseTransfer;
    }
}
