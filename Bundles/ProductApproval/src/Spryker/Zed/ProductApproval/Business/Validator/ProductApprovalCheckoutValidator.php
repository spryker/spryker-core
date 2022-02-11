<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business\Validator;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\ProductApproval\ProductApprovalConfig;
use Spryker\Zed\ProductApproval\Business\Reader\ProductReaderInterface;

class ProductApprovalCheckoutValidator implements ProductApprovalCheckoutValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_NOT_APPROVED = 'product-approval.message.not-approved';

    /**
     * @var string
     */
    protected const GLOSSARY_PARAM_SKU = '%sku%';

    /**
     * @var \Spryker\Zed\ProductApproval\Business\Reader\ProductReaderInterface
     */
    protected $productReader;

    /**
     * @param \Spryker\Zed\ProductApproval\Business\Reader\ProductReaderInterface $productReader
     */
    public function __construct(ProductReaderInterface $productReader)
    {
        $this->productReader = $productReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function validateQuoteForCheckout(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool {
        if ($checkoutResponseTransfer->getIsSuccess() === null) {
            $checkoutResponseTransfer->setIsSuccess(true);
        }

        $productAbstractTransfersIndexedByIdProductAbstract = $this->getProductAbstractTransfersIndexedByIdProductAbstract($quoteTransfer);

        if (!$productAbstractTransfersIndexedByIdProductAbstract) {
            return true;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!isset($productAbstractTransfersIndexedByIdProductAbstract[$itemTransfer->getIdProductAbstract()])) {
                continue;
            }

            $productAbstractTransfer = $productAbstractTransfersIndexedByIdProductAbstract[$itemTransfer->getIdProductAbstract()];
            if ($productAbstractTransfer->getApprovalStatus() === ProductApprovalConfig::STATUS_APPROVED) {
                continue;
            }

            $checkoutErrorTransfer = (new CheckoutErrorTransfer())
                ->setMessage(static::GLOSSARY_KEY_PRODUCT_NOT_APPROVED)
                ->setParameters([static::GLOSSARY_PARAM_SKU => $itemTransfer->getSku()]);

            $checkoutResponseTransfer->addError($checkoutErrorTransfer);
            $checkoutResponseTransfer->setIsSuccess(false);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    protected function getProductAbstractTransfersIndexedByIdProductAbstract(
        QuoteTransfer $quoteTransfer
    ): array {
        $productAbstractSkus = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAbstractSku()) {
                continue;
            }

            $productAbstractSkus[] = $itemTransfer->getAbstractSku();
        }

        if (!$productAbstractSkus) {
            return [];
        }

        return $this->productReader->getProductAbstractTransfersIndexedByIdProductAbstract($productAbstractSkus);
    }
}
