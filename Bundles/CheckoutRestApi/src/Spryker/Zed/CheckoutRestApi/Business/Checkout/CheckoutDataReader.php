<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface;
use Spryker\Zed\CheckoutRestApi\Business\Expander\CheckoutExpanderInterface;
use Spryker\Zed\CheckoutRestApi\Business\Validator\CheckoutValidatorInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeInterface;

class CheckoutDataReader implements CheckoutDataReaderInterface
{
    /**
     * @var \Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeInterface
     */
    protected $calculationFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Business\Validator\CheckoutValidatorInterface
     */
    protected $checkoutValidator;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Business\Expander\CheckoutExpanderInterface
     */
    protected $checkoutExpander;

    /**
     * @var \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface[]
     */
    protected $quoteMapperPlugins;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeInterface $calculationFacade
     * @param \Spryker\Zed\CheckoutRestApi\Business\Validator\CheckoutValidatorInterface $checkoutValidator
     * @param \Spryker\Zed\CheckoutRestApi\Business\Expander\CheckoutExpanderInterface $checkoutExpander
     * @param \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface[] $quoteMapperPlugins
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        CheckoutRestApiToCalculationFacadeInterface $calculationFacade,
        CheckoutValidatorInterface $checkoutValidator,
        CheckoutExpanderInterface $checkoutExpander,
        array $quoteMapperPlugins
    ) {
        $this->quoteReader = $quoteReader;
        $this->calculationFacade = $calculationFacade;
        $this->checkoutValidator = $checkoutValidator;
        $this->checkoutExpander = $checkoutExpander;
        $this->quoteMapperPlugins = $quoteMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    public function getCheckoutData(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestCheckoutDataResponseTransfer
    {
        $quoteTransfer = $this->quoteReader->findCustomerQuoteByUuid($restCheckoutRequestAttributesTransfer);
        $restCheckoutResponseTransfer = $this->checkoutValidator->validateQuoteInCheckoutData($quoteTransfer);

        if (!$restCheckoutResponseTransfer->getIsSuccess()) {
            return $restCheckoutResponseTransfer;
        }

        $checkoutResponseTransfer = $this->checkoutValidator->validateCheckoutData($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->createPlaceOrderErrorResponse($checkoutResponseTransfer);
        }

        $quoteTransfer = $this->recalculateQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $restCheckoutDataTransfer = (new RestCheckoutDataTransfer())->setQuote($quoteTransfer);
        $restCheckoutDataTransfer = $this->checkoutExpander->expandCheckoutData(
            $restCheckoutDataTransfer,
            $restCheckoutRequestAttributesTransfer
        );

        return (new RestCheckoutDataResponseTransfer())
            ->setIsSuccess(true)
            ->setCheckoutData($restCheckoutDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function recalculateQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $quoteTransfer = $this->executeQuoteMapperPlugins($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $quoteTransfer->requireStore()
            ->getStore()
                ->requireName();

        $quoteTransfer = $this->addItemLevelShipmentTransfer($quoteTransfer);

        return $this->calculationFacade->recalculateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeQuoteMapperPlugins(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        foreach ($this->quoteMapperPlugins as $quoteMapperPlugin) {
            $quoteTransfer = $quoteMapperPlugin->map($restCheckoutRequestAttributesTransfer, $quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addItemLevelShipmentTransfer(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment()) {
                continue;
            }

            $itemTransfer->setShipment($quoteTransfer->getShipment() ?? new ShipmentTransfer());
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    protected function createPlaceOrderErrorResponse(CheckoutResponseTransfer $checkoutResponseTransfer): RestCheckoutDataResponseTransfer
    {
        $restCheckoutDataResponseTransfer = (new RestCheckoutDataResponseTransfer())
            ->setIsSuccess(false);

        if (!$checkoutResponseTransfer->getErrors()->count()) {
            return $restCheckoutDataResponseTransfer
                ->addError(
                    (new RestCheckoutErrorTransfer())
                        ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_ORDER_NOT_PLACED)
                );
        }

        foreach ($checkoutResponseTransfer->getErrors() as $errorTransfer) {
            $restCheckoutDataResponseTransfer->addError(
                (new RestCheckoutErrorTransfer())
                    ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_ORDER_NOT_PLACED)
                    ->setDetail($errorTransfer->getMessage())
                    ->setParameters($errorTransfer->getParameters())
            );
        }

        return $restCheckoutDataResponseTransfer;
    }
}
