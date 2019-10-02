<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Expander;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableDataTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableRowTransfer;
use Spryker\Zed\ConfigurableBundleGui\Communication\Mapper\ProductListUsedByTableDataMapperInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface;

class ProductListUsedByTableDataExpander implements ProductListUsedByTableDataExpanderInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface
     */
    protected $configurableBundleFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Communication\Mapper\ProductListUsedByTableDataMapperInterface
     */
    protected $productListUsedByTableDataMapper;

    /**
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface $configurableBundleFacade
     * @param \Spryker\Zed\ConfigurableBundleGui\Communication\Mapper\ProductListUsedByTableDataMapperInterface $productListUsedByTableDataMapper
     */
    public function __construct(
        ConfigurableBundleGuiToConfigurableBundleFacadeInterface $configurableBundleFacade,
        ProductListUsedByTableDataMapperInterface $productListUsedByTableDataMapper
    ) {
        $this->configurableBundleFacade = $configurableBundleFacade;
        $this->productListUsedByTableDataMapper = $productListUsedByTableDataMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer
     */
    public function expandTableData(ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer): ProductListUsedByTableDataTransfer
    {
        $productListUsedByTableDataTransfer->getProductList()->requireIdProductList();

        $configurableBundleTemplateSlotFilterTransfer = (new ConfigurableBundleTemplateSlotFilterTransfer())->setIdProductList(
            $productListUsedByTableDataTransfer->getProductList()->getIdProductList()
        );

        $configurableBundleTemplateSlotTransfers = $this->configurableBundleFacade
            ->getConfigurableBundleTemplateSlotCollection($configurableBundleTemplateSlotFilterTransfer);

        $productListUsedByTableDataTransfer = $this->expandProductListUsedByTableDataTransfer(
            $productListUsedByTableDataTransfer,
            $configurableBundleTemplateSlotTransfers
        );

        return $productListUsedByTableDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[] $configurableBundleTemplateSlotTransfers
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer
     */
    protected function expandProductListUsedByTableDataTransfer(
        ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer,
        array $configurableBundleTemplateSlotTransfers
    ): ProductListUsedByTableDataTransfer {
        foreach ($configurableBundleTemplateSlotTransfers as $configurableBundleTemplateSlotTransfer) {
            $productListUsedByTableDataTransfer->addRow(
                $this->productListUsedByTableDataMapper->mapConfigurableBundleTemplateSlotTransferToProductListUsedByTableRowTransfer(
                    $configurableBundleTemplateSlotTransfer,
                    new ProductListUsedByTableRowTransfer()
                )
            );
        }

        return $productListUsedByTableDataTransfer;
    }
}
