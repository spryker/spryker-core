<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Expander;

use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableDataTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableRowTransfer;
use Spryker\Zed\MerchantRelationshipGui\Communication\Mapper\ProductListUsedByTableDataMapperInterface;
use Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantRelationshipFacadeInterface;

class ProductListUsedByTableDataExpander implements ProductListUsedByTableDataExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipGui\Communication\Mapper\ProductListUsedByTableDataMapperInterface
     */
    protected $productListUsedByTableDataMapper;

    /**
     * @param \Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     * @param \Spryker\Zed\MerchantRelationshipGui\Communication\Mapper\ProductListUsedByTableDataMapperInterface $productListUsedByTableDataMapper
     */
    public function __construct(
        MerchantRelationshipGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade,
        ProductListUsedByTableDataMapperInterface $productListUsedByTableDataMapper
    ) {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
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

        $merchantRelationshipFilterTransfer = (new MerchantRelationshipFilterTransfer())->setIdProductList(
            $productListUsedByTableDataTransfer->getProductList()->getIdProductList()
        );

        $merchantRelationshipTransfers = $this->merchantRelationshipFacade->getMerchantRelationshipCollection($merchantRelationshipFilterTransfer);

        $productListUsedByTableDataTransfer = $this->expandProductListUsedByTableDataTransfer(
            $productListUsedByTableDataTransfer,
            $merchantRelationshipTransfers
        );

        return $productListUsedByTableDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer[] $merchantRelationshipTransfers
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer
     */
    protected function expandProductListUsedByTableDataTransfer(
        ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer,
        array $merchantRelationshipTransfers
    ): ProductListUsedByTableDataTransfer {
        foreach ($merchantRelationshipTransfers as $merchantRelationshipTransfer) {
            $productListUsedByTableDataTransfer->addRow(
                $this->productListUsedByTableDataMapper->mapMerchantRelationshipTransferToProductListUsedByTableRowTransfer(
                    $merchantRelationshipTransfer,
                    new ProductListUsedByTableRowTransfer()
                )
            );
        }

        return $productListUsedByTableDataTransfer;
    }
}
