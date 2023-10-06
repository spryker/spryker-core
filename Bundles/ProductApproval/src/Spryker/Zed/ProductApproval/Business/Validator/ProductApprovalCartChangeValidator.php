<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business\Validator;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Shared\ProductApproval\ProductApprovalConfig;
use Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToProductFacadeInterface;

class ProductApprovalCartChangeValidator implements ProductApprovalCartChangeValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAM_SKU = '%sku%';

    /**
     * @var string
     */
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_NOT_APPROVED = 'product-approval.message.not-approved';

    /**
     * @var \Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToProductFacadeInterface $productFacade
     */
    public function __construct(ProductApprovalToProductFacadeInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateCartChange(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);
        $approvalStatusesIndexedByProductSku = $this->getApprovalStatusesIndexedByProductSku($cartChangeTransfer);

        if (!$approvalStatusesIndexedByProductSku) {
            return $cartPreCheckResponseTransfer;
        }

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $sku = $itemTransfer->getSkuOrFail();

            if (
                isset($approvalStatusesIndexedByProductSku[$sku])
                && $approvalStatusesIndexedByProductSku[$sku] === ProductApprovalConfig::STATUS_APPROVED
            ) {
                continue;
            }

            $cartPreCheckResponseTransfer->setIsSuccess(false)
                ->addMessage($this->createErrorMessage($sku));
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
            ->setValue(static::GLOSSARY_KEY_PRODUCT_NOT_APPROVED)
            ->setParameters([static::GLOSSARY_KEY_PARAM_SKU => $sku]);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<string, string|null>
     */
    protected function getApprovalStatusesIndexedByProductSku(CartChangeTransfer $cartChangeTransfer): array
    {
        $productConcreteTransfers = $this->getConcreteProducts($cartChangeTransfer);
        $productAbstractTransfers = $this->getAbstractProducts($productConcreteTransfers);

        $approvalStatusesIndexedByIdProductAbstract = [];
        foreach ($productAbstractTransfers as $productAbstractTransfer) {
            $idProductAbstract = $productAbstractTransfer->getIdProductAbstractOrFail();

            $approvalStatusesIndexedByIdProductAbstract[$idProductAbstract] = $productAbstractTransfer->getApprovalStatus();
        }

        $approvalStatusesIndexedByProductSku = [];
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productSku = $productConcreteTransfer->getSkuOrFail();
            $idProductAbstract = $productConcreteTransfer->getFkProductAbstractOrFail();

            $approvalStatusesIndexedByProductSku[$productSku] = $approvalStatusesIndexedByIdProductAbstract[$idProductAbstract];
        }

        return $approvalStatusesIndexedByProductSku;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function getConcreteProducts(CartChangeTransfer $cartChangeTransfer): array
    {
        $productSkus = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getSku()) {
                continue;
            }

            $productSkus[] = $itemTransfer->getSku();
        }

        if (!$productSkus) {
            return [];
        }

        return $this->productFacade->getRawProductConcreteTransfersByConcreteSkus($productSkus);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    protected function getAbstractProducts(array $productConcreteTransfers): array
    {
        $productAbstractSkus = [];
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productAbstractSkus[] = $productConcreteTransfer->getAbstractSkuOrFail();
        }

        if (!$productAbstractSkus) {
            return [];
        }

        return $this->productFacade->getRawProductAbstractTransfersByAbstractSkus($productAbstractSkus);
    }
}
