<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\PriceProductTableViewSorterInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductTableDataMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\PriceProductReaderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;

class PriceProductAbstractTableDataProvider extends AbstractPriceProductTableDataProvider
{
    /**
     * @var int
     */
    protected $idProductAbstract;

    /**
     * @param int $idProductConcrete
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\PriceProductReaderInterface $priceProductReader
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductTableDataMapperInterface $priceProductTableDataMapper
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\PriceProductTableViewSorterInterface $priceProductTableViewSorter
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        int $idProductConcrete,
        PriceProductReaderInterface $priceProductReader,
        PriceProductTableDataMapperInterface $priceProductTableDataMapper,
        PriceProductTableViewSorterInterface $priceProductTableViewSorter,
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
    ) {
        $this->idProductAbstract = $idProductConcrete;

        parent::__construct(
            $priceProductReader,
            $priceProductTableDataMapper,
            $priceProductTableViewSorter,
            $merchantUserFacade,
            $moneyFacade
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        /** @var \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer */
        $priceProductTableCriteriaTransfer = parent::createCriteria($guiTableDataRequestTransfer);
        $priceProductTableCriteriaTransfer->setIdProductAbstract($this->idProductAbstract);

        return $priceProductTableCriteriaTransfer;
    }
}
