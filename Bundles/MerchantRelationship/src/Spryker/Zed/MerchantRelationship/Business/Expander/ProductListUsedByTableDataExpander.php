<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Expander;

use Generated\Shared\Transfer\ProductListUsedByTableDataTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableRowTransfer;
use Spryker\Zed\MerchantRelationship\Business\Mapper\ProductListUsedByTableDataMapperInterface;
use Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface;

class ProductListUsedByTableDataExpander implements ProductListUsedByTableDataExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface
     */
    protected $merchantRelationshipReader;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Mapper\ProductListUsedByTableDataMapperInterface
     */
    protected $productListUsedByTableDataMapper;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface $merchantRelationshipReader
     * @param \Spryker\Zed\MerchantRelationship\Business\Mapper\ProductListUsedByTableDataMapperInterface $productListUsedByTableDataMapper
     */
    public function __construct(
        MerchantRelationshipReaderInterface $merchantRelationshipReader,
        ProductListUsedByTableDataMapperInterface $productListUsedByTableDataMapper
    ) {
        $this->merchantRelationshipReader = $merchantRelationshipReader;
        $this->productListUsedByTableDataMapper = $productListUsedByTableDataMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer
     */
    public function expandTableData(ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer): ProductListUsedByTableDataTransfer
    {
        $merchantRelationshipTransfers = $this->merchantRelationshipReader->getMerchantRelationshipsByProductList(
            $productListUsedByTableDataTransfer->getProductList()
        );

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
