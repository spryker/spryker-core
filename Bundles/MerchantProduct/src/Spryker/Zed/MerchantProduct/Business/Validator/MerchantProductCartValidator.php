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
    protected $merchantProductRepository;

    /**
     * @param \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface $merchantProductRepository
     */
    public function __construct(MerchantProductRepositoryInterface $merchantProductRepository)
    {
        $this->merchantProductRepository = $merchantProductRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateCartChange(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $concreteSkus = $this->extractMerchantProductConcreteSkus($cartChangeTransfer);
        $concreteProductSkuMerchantReferenceMap = $this->merchantProductRepository->getConcreteProductSkuMerchantReferenceMap($concreteSkus);

        $messageTransfers = new ArrayObject();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($this->isValidItem($itemTransfer, $concreteProductSkuMerchantReferenceMap)) {
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
     * @phpstan-param array<string, string> $concreteProductSkuMerchantReferenceMap
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $concreteProductSkuMerchantReferenceMap
     *
     * @return bool
     */
    protected function isValidItem(ItemTransfer $itemTransfer, array $concreteProductSkuMerchantReferenceMap): bool
    {
        if (!$this->isMerchantProduct($itemTransfer)) {
            return true;
        }

        if (!isset($concreteProductSkuMerchantReferenceMap[$itemTransfer->getSku()])) {
            return false;
        }

        if ($itemTransfer->getMerchantReference() !== $concreteProductSkuMerchantReferenceMap[$itemTransfer->getSku()]) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return string[]
     */
    protected function extractMerchantProductConcreteSkus(CartChangeTransfer $cartChangeTransfer): array
    {
        $merchantProductConcreteSkus = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isMerchantProduct($itemTransfer)) {
                continue;
            }

            /**
             * @var string $sku
             */
            $sku = $itemTransfer->getSku();

            $merchantProductConcreteSkus[] = $sku;
        }

        return $merchantProductConcreteSkus;
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
