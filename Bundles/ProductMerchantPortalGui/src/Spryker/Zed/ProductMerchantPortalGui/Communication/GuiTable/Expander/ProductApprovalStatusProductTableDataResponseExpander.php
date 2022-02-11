<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Expander;

use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface;

class ProductApprovalStatusProductTableDataResponseExpander implements ProductApprovalStatusProductTableDataResponseExpanderInterface
{
    /**
     * @var string
     */
    protected const EMPTY_APPROVAL_STATUS = '-';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        ProductMerchantPortalGuiToProductFacadeInterface $productFacade
    ) {
        $this->translatorFacade = $translatorFacade;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function expand(GuiTableDataResponseTransfer $guiTableDataResponseTransfer): GuiTableDataResponseTransfer
    {
        $guiTableRowDataResponseTransfers = $guiTableDataResponseTransfer->getRows();

        $abstractSkus = array_map(function (GuiTableRowDataResponseTransfer $guiTableRowDataResponseTransfer) {
            return $guiTableRowDataResponseTransfer->getResponseData()[ProductConcreteTransfer::ABSTRACT_SKU];
        }, $guiTableRowDataResponseTransfers->getArrayCopy());
        $productAbstractTransfers = $this->productFacade->getRawProductAbstractTransfersByAbstractSkus(
            array_unique($abstractSkus),
        );
        $productApprovalStatusesIndexedByAbstractSku = $this->getProductApprovalStatusesIndexedByAbstractSku(
            $productAbstractTransfers,
        );

        foreach ($guiTableRowDataResponseTransfers as $guiTableRowDataResponseTransfer) {
            $responseData = $guiTableRowDataResponseTransfer->getResponseData();
            $productApprovalStatus = $productApprovalStatusesIndexedByAbstractSku[$responseData[ProductConcreteTransfer::ABSTRACT_SKU]];
            $responseData[ProductAbstractTransfer::APPROVAL_STATUS] = $this->translatorFacade->trans($productApprovalStatus);

            $guiTableRowDataResponseTransfer->setResponseData($responseData);
        }

        return $guiTableDataResponseTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfers
     *
     * @return array<string, string>
     */
    protected function getProductApprovalStatusesIndexedByAbstractSku(array $productAbstractTransfers): array
    {
        $productApprovalStatusesIndexedByAbstractSku = [];
        foreach ($productAbstractTransfers as $productAbstractTransfer) {
            $productApprovalStatus = $productAbstractTransfer->getApprovalStatus() ?? static::EMPTY_APPROVAL_STATUS;
            $productApprovalStatusesIndexedByAbstractSku[$productAbstractTransfer->getSkuOrFail()] = $productApprovalStatus;
        }

        return $productApprovalStatusesIndexedByAbstractSku;
    }
}
