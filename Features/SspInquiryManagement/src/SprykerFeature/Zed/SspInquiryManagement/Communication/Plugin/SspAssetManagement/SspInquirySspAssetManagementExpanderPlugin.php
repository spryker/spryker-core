<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication\Plugin\SspAssetManagement;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryOwnerConditionGroupTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Zed\SspAssetManagement\Dependency\Plugin\SspAssetManagementExpanderPluginInterface;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\Communication\SspInquiryManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface getRepository()
 */
class SspInquirySspAssetManagementExpanderPlugin extends AbstractPlugin implements SspAssetManagementExpanderPluginInterface
{
    use PermissionAwareTrait;

    /**
     * {@inheritDoc}
     * - Expands the SspAssetCollectionTransfer with ssp inquiries data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer $sspAssetCollectionTransfer
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function expand(
        SspAssetCollectionTransfer $sspAssetCollectionTransfer,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
    ): SspAssetCollectionTransfer {
        if (!$sspAssetCriteriaTransfer->getInclude() || !$sspAssetCriteriaTransfer->getInclude()->getWithSspInquiries()) {
            return $sspAssetCollectionTransfer;
        }

        $sspInquiryOwnerConditionGroupTransfer = new SspInquiryOwnerConditionGroupTransfer();
        $sspInquiryOwnerConditionGroupTransfer->setFkCompany($sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->getAssignedBusinessUnitCompanyId());
        $sspInquiryOwnerConditionGroupTransfer->setFkCompanyBusinessUnit($sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->getAssignedBusinessUnitId());

        $sspAssetIds = [];
        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            if ($sspAssetTransfer->getIdSspAsset()) {
                $sspAssetIds[] = $sspAssetTransfer->getIdSspAsset();
            }
        }

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(1)
            ->setMaxPerPage(3);

        $sspInquiryCollectionTransfer = $this->getFacade()->getSspInquiryCollection(
            (new SspInquiryCriteriaTransfer())
                ->addSort((new SortTransfer())->setIsAscending(false)->setField('created_at'))
                ->setSspInquiryConditions(
                    (new SspInquiryConditionsTransfer())
                        ->setSspAssetIds($sspAssetIds)
                        ->setSspInquiryOwnerConditionGroup($sspInquiryOwnerConditionGroupTransfer),
                )
                ->setPagination($paginationTransfer),
        );

        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $sspAssetTransfer->setSspInquiryCollection((new SspInquiryCollectionTransfer())->setPagination($sspInquiryCollectionTransfer->getPagination()));
            foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
                if ($sspInquiryTransfer->getSspAssetOrFail()->getIdSspAsset() === $sspAssetTransfer->getIdSspAsset()) {
                    $sspAssetTransfer->getSspInquiryCollectionOrFail()->addSspInquiry($sspInquiryTransfer);
                }
            }
        }

        return $sspAssetCollectionTransfer;
    }
}
