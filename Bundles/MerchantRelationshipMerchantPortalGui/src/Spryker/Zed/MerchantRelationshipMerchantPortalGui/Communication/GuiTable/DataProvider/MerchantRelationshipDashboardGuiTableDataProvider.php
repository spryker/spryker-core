<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\DataProvider;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationshipDashboardGuiTableConfigurationProvider;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\MerchantRelationshipMerchantPortalGuiConfig;

class MerchantRelationshipDashboardGuiTableDataProvider implements MerchantRelationshipDashboardGuiTableDataProviderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\MerchantRelationshipMerchantPortalGuiConfig
     */
    protected MerchantRelationshipMerchantPortalGuiConfig $merchantRelationshipMerchantPortalGuiConfig;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\MerchantRelationshipMerchantPortalGuiConfig $merchantRelationshipMerchantPortalGuiConfig
     */
    public function __construct(MerchantRelationshipMerchantPortalGuiConfig $merchantRelationshipMerchantPortalGuiConfig)
    {
        $this->merchantRelationshipMerchantPortalGuiConfig = $merchantRelationshipMerchantPortalGuiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return list<array<string, string|int|bool>>
     */
    public function fetchData(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): array {
        $merchantRelationshipTransfersGroupedByIdCompany = $this->getMerchantRelationshipsGroupedByIdCompany(
            $merchantRelationshipCollectionTransfer,
        );
        $merchantRelationshipTransfersGroupedByIdCompany = $this->sortMerchantRelationshipsByNumberOfRelationshipsWithCompany(
            $merchantRelationshipTransfersGroupedByIdCompany,
        );
        $companyTransfers = $this->extractCompanyTransfers($merchantRelationshipCollectionTransfer);

        $data = [];
        $numberOfRows = 0;
        foreach ($companyTransfers as $companyTransfer) {
            $totalMerchantRelationshipsCount = count($merchantRelationshipTransfersGroupedByIdCompany[$companyTransfer->getIdCompanyOrFail()]);
            $data[] = $this->createRowData($companyTransfer, $totalMerchantRelationshipsCount);

            $numberOfRows++;
            if ($this->merchantRelationshipMerchantPortalGuiConfig->getMerchantRelationshipDashboardTableRowLimit() === $numberOfRows) {
                break;
            }
        }

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return array<int, list<\Generated\Shared\Transfer\MerchantRelationshipTransfer>>
     */
    protected function getMerchantRelationshipsGroupedByIdCompany(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): array {
        $groupedMerchantRelationshipTransfers = [];
        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $companyTransfer = $merchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail()->getCompanyOrFail();
            $groupedMerchantRelationshipTransfers[$companyTransfer->getIdCompanyOrFail()][] = $merchantRelationshipTransfer;
        }

        return $groupedMerchantRelationshipTransfers;
    }

    /**
     * @param array<int, list<\Generated\Shared\Transfer\MerchantRelationshipTransfer>> $merchantRelationshipTransfersGroupedByIdCompany
     *
     * @return array<int, list<\Generated\Shared\Transfer\MerchantRelationshipTransfer>>
     */
    protected function sortMerchantRelationshipsByNumberOfRelationshipsWithCompany(array $merchantRelationshipTransfersGroupedByIdCompany): array
    {
        uasort($merchantRelationshipTransfersGroupedByIdCompany, function ($a, $b) {
            return count($a) <=> count($b);
        });

        return $merchantRelationshipTransfersGroupedByIdCompany;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return list<\Generated\Shared\Transfer\CompanyTransfer>
     */
    protected function extractCompanyTransfers(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): array {
        $companyTransfers = [];
        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $companyTransfer = $merchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail()->getCompanyOrFail();
            $companyTransfers[$companyTransfer->getIdCompanyOrFail()] = $companyTransfer;
        }

        return array_values($companyTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param int $totalMerchantRelationshipsCount
     *
     * @return array<string, mixed>
     */
    protected function createRowData(CompanyTransfer $companyTransfer, int $totalMerchantRelationshipsCount): array
    {
        return [
            MerchantRelationshipDashboardGuiTableConfigurationProvider::COL_KEY_COMPANY => $companyTransfer->getNameOrFail(),
            MerchantRelationshipDashboardGuiTableConfigurationProvider::COL_KEY_RELATIONS => $totalMerchantRelationshipsCount,
            MerchantRelationshipDashboardGuiTableConfigurationProvider::COL_KEY_VIEW => true,
            CompanyTransfer::ID_COMPANY => $companyTransfer->getIdCompanyOrFail(),
        ];
    }
}
