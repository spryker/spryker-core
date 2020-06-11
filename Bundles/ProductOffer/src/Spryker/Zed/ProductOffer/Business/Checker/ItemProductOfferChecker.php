<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Checker;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface;

class ItemProductOfferChecker implements ItemProductOfferCheckerInterface
{
    protected const GLOSSARY_KEY_ERROR_INVALID_PRODUCT_OFFER_REFERENCE = 'product-offer.info.reference.invalid';
    protected const GLOSSARY_KEY_PARAM_SKU = '%sku%';
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @var \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface
     */
    protected $productOfferRepository;

    /**
     * @param \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface $productOfferRepository
     */
    public function __construct(
        ProductOfferRepositoryInterface $productOfferRepository
    ) {
        $this->productOfferRepository = $productOfferRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkItemProductOffer(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);

        $productConcreteSkusByOfferReference = [];
        foreach ($cartChangeTransfer->getItems() as $cartItem) {
            if (!$cartItem->getProductOfferReference()) {
                continue;
            }

            $productConcreteSkusByOfferReference[$cartItem->getProductOfferReference()] = $cartItem->getSku();
        }

        $productOfferReferences = array_keys($productConcreteSkusByOfferReference);
        if (!$productOfferReferences) {
            return $cartPreCheckResponseTransfer;
        }

        $productOfferCriteriaFilterTransfer = (new ProductOfferCriteriaFilterTransfer())
            ->setProductOfferReferences($productOfferReferences)
            ->setIsActive(true);

        $productOfferTransfers = $this->productOfferRepository
            ->find($productOfferCriteriaFilterTransfer)
            ->getProductOffers();

        if (!$productOfferTransfers->count()) {
            $cartPreCheckResponseTransfer->setIsSuccess(false);
            foreach ($productConcreteSkusByOfferReference as $sku => $productOfferReference) {
                $cartPreCheckResponseTransfer->addMessage($this->createErrorMessage($sku));
            }

            return $cartPreCheckResponseTransfer;
        }

        foreach ($productOfferTransfers as $productOfferTransfer) {
            if (
                isset($productConcreteSkusByOfferReference[$productOfferTransfer->getProductOfferReference()])
                && $productOfferTransfer->getConcreteSku() === $productConcreteSkusByOfferReference[$productOfferTransfer->getProductOfferReference()]
            ) {
                continue;
            }

            $cartPreCheckResponseTransfer->setIsSuccess(false)
                ->addMessage($this->createErrorMessage($productOfferTransfer->getConcreteSku()));
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createErrorMessage(string $sku): MessageTransfer
    {
        return (new MessageTransfer())
            ->setType(static::MESSAGE_TYPE_ERROR)
            ->setValue(static::GLOSSARY_KEY_ERROR_INVALID_PRODUCT_OFFER_REFERENCE)
            ->setParameters([static::GLOSSARY_KEY_PARAM_SKU => $sku]);
    }
}
