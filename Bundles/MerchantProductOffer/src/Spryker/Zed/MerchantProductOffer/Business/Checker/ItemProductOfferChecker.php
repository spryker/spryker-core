<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business\Checker;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferRepositoryInterface;

class ItemProductOfferChecker implements ItemProductOfferCheckerInterface
{
    protected const GLOSSARY_KEY_ERROR_INVALID_PRODUCT_OFFER_REFERENCE = 'product-offer.info.reference.invalid';
    protected const GLOSSARY_KEY_PARAM_SKU = '%sku%';
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @var \Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferRepositoryInterface
     */
    protected $merchantProductOfferRepository;

    /**
     * @param \Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferRepositoryInterface $merchantProductOfferRepository
     */
    public function __construct(
        MerchantProductOfferRepositoryInterface $merchantProductOfferRepository
    ) {
        $this->merchantProductOfferRepository = $merchantProductOfferRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkItemProductOffer(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);

        $itemsWithOffers = [];
        foreach ($cartChangeTransfer->getItems() as $cartItem) {
            if (!$cartItem->getProductOfferReference()) {
                continue;
            }

            $itemsWithOffers[$cartItem->getSku()] = $cartItem->getProductOfferReference();
        }

        $productOfferCriteriaFilterTransfer = new MerchantProductOfferCriteriaFilterTransfer();
        $productOfferCriteriaFilterTransfer->setSkus(array_keys($itemsWithOffers));

        /** @var \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer */
        $productOfferCollectionTransfer = $this->merchantProductOfferRepository
            ->getProductOfferCollectionTransfer($productOfferCriteriaFilterTransfer);

        $productOffers = $productOfferCollectionTransfer->getProductOffers()->getArrayCopy();
        if (!$productOffers) {
            $cartPreCheckResponseTransfer->setIsSuccess(false);
            foreach ($itemsWithOffers as $sku => $productOfferReference) {
                $cartPreCheckResponseTransfer->addMessage($this->createMessage($sku));
            }

            return $cartPreCheckResponseTransfer;
        }

        foreach ($productOffers as $productOffer) {
            if ($productOffer->getProductOfferReference() === $itemsWithOffers[$productOffer->getConcreteSku()]) {
                continue;
            }

            $cartPreCheckResponseTransfer->setIsSuccess(false)
                ->addMessage($this->createMessage($productOffer->getConcreteSku()));
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessage(string $sku): MessageTransfer
    {
        return (new MessageTransfer())
            ->setType(static::MESSAGE_TYPE_ERROR)
            ->setValue(static::GLOSSARY_KEY_ERROR_INVALID_PRODUCT_OFFER_REFERENCE)
            ->setParameters([static::GLOSSARY_KEY_PARAM_SKU => $sku]);
    }
}
