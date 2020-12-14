<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantProductCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface;

class MerchantProductCartValidator implements MerchantProductCartValidatorInterface
{
    protected const MESSAGE_TYPE_ERROR = 'error';
    protected const GLOSSARY_KEY_INVALID_MERCHANT_PRODUCT = 'merchant_product.message.invalid';
    protected const GLOSSARY_PARAM_MERCHANT_REFERENCE = '%merchant_reference%';
    protected const GLOSSARY_PARAM_CONCRETE_SKU = '%sku%';

    /**
     * @var \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface $repository
     */
    public function __construct(MerchantProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateCartChange(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $merchantProductSkus = $this->extractMerchantProductSkus($cartChangeTransfer);
        $merchantProductCollectionTransfer = $this->repository->get(
            (new MerchantProductCriteriaTransfer())
                ->setSkus($merchantProductSkus)
        );

        $messageTransfers = new ArrayObject();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($this->isValidItem($itemTransfer, $merchantProductCollectionTransfer)) {
                continue;
            }

            $messageTransfers->append(
                (new MessageTransfer())
                    ->setType(static::MESSAGE_TYPE_ERROR)
                    ->setValue(static::GLOSSARY_KEY_INVALID_MERCHANT_PRODUCT)
                    ->setParameters([
                        static::GLOSSARY_PARAM_MERCHANT_REFERENCE => $itemTransfer->getMerchantReference(),
                        static::GLOSSARY_PARAM_CONCRETE_SKU => $itemTransfer->getSku(),
                    ])
            );
        }

        return (new CartPreCheckResponseTransfer())
            ->setMessages($messageTransfers)
            ->setIsSuccess(!$messageTransfers->count());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\MerchantProductCollectionTransfer $merchantProductCollectionTransfer
     *
     * @return bool
     */
    protected function isValidItem(ItemTransfer $itemTransfer, MerchantProductCollectionTransfer $merchantProductCollectionTransfer): bool
    {
        if (!$this->isMerchantProduct($itemTransfer)) {
            return true;
        }

        foreach ($merchantProductCollectionTransfer->getMerchantProducts() as $merchantProductTransfer) {
            if ($itemTransfer->getMerchantReference() !== $merchantProductTransfer->getMerchantReference()) {
                continue;
            }

            if ($itemTransfer->getAbstractSku() !== $merchantProductTransfer->getSku()) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return string[]
     */
    protected function extractMerchantProductSkus(CartChangeTransfer $cartChangeTransfer): array
    {
        $merchantProductSkus = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isMerchantProduct($itemTransfer)) {
                continue;
            }

            /**
             * @var string $sku
             */
            $sku = $itemTransfer->getSku();

            $merchantProductSkus[] = $sku;
        }

        return $merchantProductSkus;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isMerchantProduct(ItemTransfer $itemTransfer): bool
    {
        if (!$itemTransfer->getMerchantReference()) {
            return false;
        }

        if ($itemTransfer->getProductOfferReference()) {
            return false;
        }

        return true;
    }
}
