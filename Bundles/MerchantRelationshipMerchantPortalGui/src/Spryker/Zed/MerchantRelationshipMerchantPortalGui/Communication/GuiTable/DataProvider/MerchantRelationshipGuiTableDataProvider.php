<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\SortCollectionTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationshipGuiTableConfigurationProvider;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\Mapper\MerchantRelationshipGuiTableMapperInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface;

class MerchantRelationshipGuiTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @uses \Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap::COL_ID_MERCHANT_RELATIONSHIP
     *
     * @var string
     */
    protected const COL_ID_MERCHANT_RELATIONSHIP = 'spy_merchant_relationship.id_merchant_relationship';

    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\Mapper\MerchantRelationshipGuiTableMapperInterface
     */
    protected MerchantRelationshipGuiTableMapperInterface $merchantRelationshipGuiTableMapper;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface
     */
    protected MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\Mapper\MerchantRelationshipGuiTableMapperInterface $merchantRelationshipGuiTableMapper
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        MerchantRelationshipGuiTableMapperInterface $merchantRelationshipGuiTableMapper,
        MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade,
        MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
    ) {
        $this->merchantRelationshipGuiTableMapper = $merchantRelationshipGuiTableMapper;
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        return new MerchantRelationshipTableCriteriaTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $merchantRelationshipCriteriaTransfer = $this->createMerchantRelationshipCriteriaTransfer($criteriaTransfer);
        /** @var \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer */
        $merchantRelationshipCollectionTransfer = $this->merchantRelationshipFacade
            ->getMerchantRelationshipCollection(null, $merchantRelationshipCriteriaTransfer);
        $guiTableDataResponseTransfer = $this->merchantRelationshipGuiTableMapper
            ->mapMerchantRelationshipCollectionTransferToGuiTableDataResponseTransfer(
                $merchantRelationshipCollectionTransfer,
                new GuiTableDataResponseTransfer(),
            );

        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $guiTableDataResponseTransfer->addRow($this->getRowData($merchantRelationshipTransfer));
        }

        return $guiTableDataResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableRowDataResponseTransfer
     */
    protected function getRowData(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): GuiTableRowDataResponseTransfer {
        $companyBusinessUnitTransfer = $merchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail();

        $rowData = [
            MerchantRelationshipGuiTableConfigurationProvider::COL_KEY_ID => $merchantRelationshipTransfer->getIdMerchantRelationship(),
            MerchantRelationshipGuiTableConfigurationProvider::COL_KEY_COMPANY => $companyBusinessUnitTransfer->getCompanyOrFail()->getName(),
            MerchantRelationshipGuiTableConfigurationProvider::COL_KEY_BUSINESS_UNIT_OWNER => $companyBusinessUnitTransfer->getName(),
            MerchantRelationshipGuiTableConfigurationProvider::COL_KEY_BUSINESS_UNITS => $this->extractAssigneeCompanyBusinessUnitNames($merchantRelationshipTransfer),
            MerchantRelationshipGuiTableConfigurationProvider::COL_KEY_CREATED_AT => $merchantRelationshipTransfer->getCreatedAt(),
        ];

        return (new GuiTableRowDataResponseTransfer())->setResponseData($rowData);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTableCriteriaTransfer $merchantRelationshipTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer
     */
    protected function createMerchantRelationshipCriteriaTransfer(
        MerchantRelationshipTableCriteriaTransfer $merchantRelationshipTableCriteriaTransfer
    ): MerchantRelationshipCriteriaTransfer {
        $merchantUserTransfer = $this->merchantUserFacade->getCurrentMerchantUser();
        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->addIdMerchant($merchantUserTransfer->getIdMerchantOrFail());

        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer);

        $merchantRelationshipCriteriaTransfer = $this->merchantRelationshipGuiTableMapper
            ->mapMerchantRelationshipTableCriteriaTransferToMerchantRelationshipCriteriaTransfer(
                $merchantRelationshipTableCriteriaTransfer,
                $merchantRelationshipCriteriaTransfer,
            );

        if (
            $merchantRelationshipCriteriaTransfer->getSortCollection() !== null
            && $merchantRelationshipCriteriaTransfer->getSortCollection()->getSorts()->count() !== 0
        ) {
            return $merchantRelationshipCriteriaTransfer;
        }

        return $this->addDefaultSorting($merchantRelationshipCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return list<string>
     */
    protected function extractAssigneeCompanyBusinessUnitNames(MerchantRelationshipTransfer $merchantRelationshipTransfer): array
    {
        if (!$merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()) {
            return [];
        }

        $companyBusinessUnitNames = [];
        foreach ($merchantRelationshipTransfer->getAssigneeCompanyBusinessUnitsOrFail()->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $companyBusinessUnitNames[] = $companyBusinessUnitTransfer->getNameOrFail();
        }

        return $companyBusinessUnitNames;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer
     */
    protected function addDefaultSorting(
        MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
    ): MerchantRelationshipCriteriaTransfer {
        $sortTransfer = (new SortTransfer())
            ->setField(static::COL_ID_MERCHANT_RELATIONSHIP)
            ->setIsAscending(false);

        return $merchantRelationshipCriteriaTransfer->setSortCollection(
            (new SortCollectionTransfer())->addSort($sortTransfer),
        );
    }
}
