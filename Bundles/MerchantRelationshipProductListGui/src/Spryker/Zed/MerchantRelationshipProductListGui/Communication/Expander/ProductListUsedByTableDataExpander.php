<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\Expander;

use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableDataTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableRowTransfer;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\Mapper\ProductListUsedByTableDataMapperInterface;
use Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipProductListFacadeInterface;

class ProductListUsedByTableDataExpander implements ProductListUsedByTableDataExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipProductListFacadeInterface
     */
    protected $merchantRelationshipProductListFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipProductListGui\Communication\Mapper\ProductListUsedByTableDataMapperInterface
     */
    protected $productListUsedByTableDataMapper;

    /**
     * @param \Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     * @param \Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipProductListFacadeInterface $merchantRelationshipProductListFacade
     * @param \Spryker\Zed\MerchantRelationshipProductListGui\Communication\Mapper\ProductListUsedByTableDataMapperInterface $productListUsedByTableDataMapper
     */
    public function __construct(
        MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade,
        MerchantRelationshipProductListGuiToMerchantRelationshipProductListFacadeInterface $merchantRelationshipProductListFacade,
        ProductListUsedByTableDataMapperInterface $productListUsedByTableDataMapper
    ) {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
        $this->productListUsedByTableDataMapper = $productListUsedByTableDataMapper;
        $this->merchantRelationshipProductListFacade = $merchantRelationshipProductListFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer
     */
    public function expandTableData(ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer): ProductListUsedByTableDataTransfer
    {
        $productListUsedByTableDataTransfer->getProductList()->requireIdProductList();

        $merchantRelationshipTransfers = $this->getMerchantRelationshipTransfers($productListUsedByTableDataTransfer->getProductList());

        $productListUsedByTableDataTransfer = $this->expandProductListUsedByTableDataTransfer(
            $productListUsedByTableDataTransfer,
            $merchantRelationshipTransfers
        );

        return $productListUsedByTableDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    protected function getMerchantRelationshipTransfers(ProductListTransfer $productListTransfer): array
    {
        $merchantRelationshipIds = $this->merchantRelationshipProductListFacade
            ->getMerchantRelationshipIdsByProductList($productListTransfer);

        if (!$merchantRelationshipIds) {
            return [];
        }

        $merchantRelationshipFilterTransfer = (new MerchantRelationshipFilterTransfer())->setMerchantRelationshipIds($merchantRelationshipIds);

        return $this->merchantRelationshipFacade->getMerchantRelationshipCollection($merchantRelationshipFilterTransfer);
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
