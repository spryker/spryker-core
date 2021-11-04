<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Validator;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\ProductOffer\ProductOfferConfig;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface;

class ProductOfferCheckoutValidator implements ProductOfferCheckoutValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_OFFER_NOT_ACTIVE_OR_APPROVED = 'product-offer.message.not-active-or-approved';

    /**
     * @var string
     */
    protected const GLOSSARY_PARAM_SKU = '%sku%';

    /**
     * @var \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface
     */
    protected $productOfferRepository;

    /**
     * @param \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface $productOfferRepository
     */
    public function __construct(ProductOfferRepositoryInterface $productOfferRepository)
    {
        $this->productOfferRepository = $productOfferRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isQuoteReadyForCheckout(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool {
        $checkoutResponseTransfer->setIsSuccess(true);
        $productOfferTransfersByProductOfferReference = $this
            ->groupProductOfferTransfersByProductOfferReference($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getProductOfferReference()) {
                continue;
            }

            if (!isset($productOfferTransfersByProductOfferReference[$itemTransfer->getProductOfferReference()])) {
                $checkoutErrorTransfer = (new CheckoutErrorTransfer())
                    ->setMessage(static::GLOSSARY_KEY_PRODUCT_OFFER_NOT_ACTIVE_OR_APPROVED)
                    ->setParameters([static::GLOSSARY_PARAM_SKU => $itemTransfer->getSku()]);

                $checkoutResponseTransfer->addError($checkoutErrorTransfer);
                $checkoutResponseTransfer->setIsSuccess(false);
            }
        }

        return $checkoutResponseTransfer->getIsSuccess();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferTransfer>
     */
    protected function groupProductOfferTransfersByProductOfferReference(
        QuoteTransfer $quoteTransfer
    ): array {
        $productOfferTransfers = [];

        $productOfferCollectionTransfer = $this->getProductOfferCollectionTransfer($quoteTransfer);
        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $productOfferTransfers[$productOfferTransfer->getProductOfferReference()] = $productOfferTransfer;
        }

        return $productOfferTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    protected function getProductOfferCollectionTransfer(
        QuoteTransfer $quoteTransfer
    ): ProductOfferCollectionTransfer {
        $productOfferCriteriaFilterTransfer = (new ProductOfferCriteriaTransfer())
            ->setIsActive(true)
            ->setApprovalStatuses([ProductOfferConfig::STATUS_APPROVED])
            ->setProductOfferReferences(
                $this->extractProductOfferReferences($quoteTransfer),
            );

        return $this->productOfferRepository->get($productOfferCriteriaFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string>
     */
    protected function extractProductOfferReferences(QuoteTransfer $quoteTransfer): array
    {
        $productOfferReferences = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference()) {
                continue;
            }
            $productOfferReferences[] = $itemTransfer->getProductOfferReference();
        }

        return $productOfferReferences;
    }
}
