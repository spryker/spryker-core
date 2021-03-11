<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider;


use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface;

class PriceProductConcreteTableDataProvider extends PriceProductTableDataProvider
{
    /**
     * @var int
     */
    protected $idProductConcrete;

    /**
     * @param int $idProductConcrete
     * @param \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface $productMerchantPortalGuiRepository
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        int $idProductConcrete,
        ProductMerchantPortalGuiRepositoryInterface $productMerchantPortalGuiRepository,
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
    ) {
        $this->idProductConcrete = $idProductConcrete;

        parent::__construct($productMerchantPortalGuiRepository, $merchantUserFacade, $moneyFacade);
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        /** @var \Generated\Shared\Transfer\PriceProductAbstractTableCriteriaTransfer $priceProductAbstractTableCriteriaTransfer */
        $priceProductAbstractTableCriteriaTransfer = parent::createCriteria($guiTableDataRequestTransfer);
        $priceProductAbstractTableCriteriaTransfer->setIdProductConcrete($this->idProductConcrete);

        return $priceProductAbstractTableCriteriaTransfer;
    }
}