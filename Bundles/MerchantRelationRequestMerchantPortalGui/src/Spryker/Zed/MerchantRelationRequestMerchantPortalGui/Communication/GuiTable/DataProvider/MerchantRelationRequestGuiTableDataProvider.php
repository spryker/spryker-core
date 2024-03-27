<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\GuiTable\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationRequestGuiTableConfigurationProvider;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\GuiTable\Mapper\MerchantRelationRequestGuiTableMapperInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface;

class MerchantRelationRequestGuiTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @uses \Orm\Zed\MerchantRelationRequest\Persistence\Map\SpyMerchantRelationRequestTableMap::COL_ID_MERCHANT_RELATION_REQUEST
     *
     * @var string
     */
    protected const COL_ID_MERCHANT_RELATION_REQUEST = 'spy_merchant_relation_request.id_merchant_relation_request';

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface
     */
    protected MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\GuiTable\Mapper\MerchantRelationRequestGuiTableMapperInterface
     */
    protected MerchantRelationRequestGuiTableMapperInterface $merchantRelationRequestGuiTableMapper;

    /**
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\GuiTable\Mapper\MerchantRelationRequestGuiTableMapperInterface $merchantRelationRequestGuiTableMapper
     */
    public function __construct(
        MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade,
        MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        MerchantRelationRequestGuiTableMapperInterface $merchantRelationRequestGuiTableMapper
    ) {
        $this->merchantRelationRequestFacade = $merchantRelationRequestFacade;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->translatorFacade = $translatorFacade;
        $this->merchantRelationRequestGuiTableMapper = $merchantRelationRequestGuiTableMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        return new MerchantRelationRequestTableCriteriaTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $merchantRelationRequestCriteriaTransfer = $this->getMerchantRelationRequestCriteriaTransfer($criteriaTransfer);
        $merchantRelationRequestCollectionTransfer = $this->merchantRelationRequestFacade
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);
        $guiTableDataResponseTransfer = $this->merchantRelationRequestGuiTableMapper
            ->mapMerchantRelationRequestCollectionTransferToGuiTableDataResponseTransfer(
                $merchantRelationRequestCollectionTransfer,
                new GuiTableDataResponseTransfer(),
            );

        foreach ($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests() as $merchantRelationRequestTransfer) {
            $guiTableDataResponseTransfer->addRow($this->getRowData($merchantRelationRequestTransfer));
        }

        return $guiTableDataResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableRowDataResponseTransfer
     */
    protected function getRowData(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): GuiTableRowDataResponseTransfer {
        $companyBusinessUnitTransfer = $merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail();

        $rowData = [
            MerchantRelationRequestGuiTableConfigurationProvider::COL_KEY_ID => $merchantRelationRequestTransfer->getIdMerchantRelationRequest(),
            MerchantRelationRequestGuiTableConfigurationProvider::COL_KEY_COMPANY => $companyBusinessUnitTransfer->getCompanyOrFail()->getName(),
            MerchantRelationRequestGuiTableConfigurationProvider::COL_KEY_BUSINESS_UNIT_OWNER => $companyBusinessUnitTransfer->getName(),
            MerchantRelationRequestGuiTableConfigurationProvider::COL_KEY_BUSINESS_UNITS => $this->getBusinessUnits($merchantRelationRequestTransfer),
            MerchantRelationRequestGuiTableConfigurationProvider::COL_KEY_CREATED_AT => $merchantRelationRequestTransfer->getCreatedAt(),
            MerchantRelationRequestGuiTableConfigurationProvider::COL_KEY_STATUS => $this->translatorFacade->trans($merchantRelationRequestTransfer->getStatusOrFail()),
        ];

        return (new GuiTableRowDataResponseTransfer())->setResponseData($rowData);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTableCriteriaTransfer $merchantRelationRequestTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer
     */
    protected function getMerchantRelationRequestCriteriaTransfer(
        MerchantRelationRequestTableCriteriaTransfer $merchantRelationRequestTableCriteriaTransfer
    ): MerchantRelationRequestCriteriaTransfer {
        $merchantRelationRequestCriteriaTransfer = $this->merchantRelationRequestGuiTableMapper
            ->mapMerchantUserTransferToMerchantRelationRequestCriteriaTransfer(
                $this->merchantUserFacade->getCurrentMerchantUser(),
                new MerchantRelationRequestCriteriaTransfer(),
            );

        $merchantRelationRequestCriteriaTransfer->getMerchantRelationRequestConditionsOrFail()
            ->setWithAssigneeCompanyBusinessUnitRelations(true);

        $merchantRelationRequestCriteriaTransfer = $this->merchantRelationRequestGuiTableMapper
            ->mapMerchantRelationRequestTableCriteriaTransferToMerchantRelationRequestCriteriaTransfer(
                $merchantRelationRequestTableCriteriaTransfer,
                $merchantRelationRequestCriteriaTransfer,
            );
        if ($merchantRelationRequestCriteriaTransfer->getSortCollection()->count() !== 0) {
            return $merchantRelationRequestCriteriaTransfer;
        }

        return $this->addDefaultSorting($merchantRelationRequestCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return array<string>
     */
    protected function getBusinessUnits(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): array
    {
        $businessUnits = [];

        foreach ($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $businessUnits[] = $companyBusinessUnitTransfer->getNameOrFail();
        }

        return $businessUnits;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer
     */
    protected function addDefaultSorting(
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): MerchantRelationRequestCriteriaTransfer {
        $sortTransfer = (new SortTransfer())
            ->setField(static::COL_ID_MERCHANT_RELATION_REQUEST)
            ->setIsAscending(false);

        return $merchantRelationRequestCriteriaTransfer->addSort($sortTransfer);
    }
}
