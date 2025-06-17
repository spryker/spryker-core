<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryOwnerConditionGroupTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Reader\SspInquiryReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspInquirySspAssetExpander implements SspInquirySspAssetExpanderInterface
{
    /**
     * @var string
     */
    protected const DEFAULT_SORT_FIELD = 'created_at';

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Reader\SspInquiryReaderInterface $sspInquiryReader
     * @param \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig $config
     */
    public function __construct(
        protected SspInquiryReaderInterface $sspInquiryReader,
        protected SelfServicePortalConfig $config
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer $sspAssetCollectionTransfer
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function expandAssetCollectionWithSspInquiries(
        SspAssetCollectionTransfer $sspAssetCollectionTransfer,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
    ): SspAssetCollectionTransfer {
        if (!$sspAssetCriteriaTransfer->getInclude() || !$sspAssetCriteriaTransfer->getInclude()->getWithSspInquiries()) {
            return $sspAssetCollectionTransfer;
        }

        $sspInquiryOwnerConditionGroupTransfer = $this->createSspInquiryOwnerConditionGroupTransfer($sspAssetCriteriaTransfer);
        $sspAssetIds = $this->extractSspAssetIds($sspAssetCollectionTransfer);
        $sspInquiryCollectionTransfer = $this->getSspInquiryCollectionTransfer($sspAssetIds, $sspInquiryOwnerConditionGroupTransfer);

        return $this->expandAssetsWithInquiries($sspAssetCollectionTransfer, $sspInquiryCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryOwnerConditionGroupTransfer
     */
    protected function createSspInquiryOwnerConditionGroupTransfer(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer): SspInquiryOwnerConditionGroupTransfer
    {
        $sspInquiryOwnerConditionGroupTransfer = new SspInquiryOwnerConditionGroupTransfer();
        $sspInquiryOwnerConditionGroupTransfer->setIdCompany($sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->getAssignedBusinessUnitCompanyId());
        $sspInquiryOwnerConditionGroupTransfer->setIdCompanyBusinessUnit($sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->getAssignedBusinessUnitId());

        return $sspInquiryOwnerConditionGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer $sspAssetCollectionTransfer
     *
     * @return list<int>
     */
    protected function extractSspAssetIds(SspAssetCollectionTransfer $sspAssetCollectionTransfer): array
    {
        $sspAssetIds = [];
        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            if ($sspAssetTransfer->getIdSspAsset()) {
                $sspAssetIds[] = $sspAssetTransfer->getIdSspAsset();
            }
        }

        return $sspAssetIds;
    }

    /**
     * @param list<int> $sspAssetIds
     * @param \Generated\Shared\Transfer\SspInquiryOwnerConditionGroupTransfer $sspInquiryOwnerConditionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    protected function getSspInquiryCollectionTransfer(
        array $sspAssetIds,
        SspInquiryOwnerConditionGroupTransfer $sspInquiryOwnerConditionGroupTransfer
    ): SspInquiryCollectionTransfer {
        $paginationTransfer = (new PaginationTransfer())
            ->setPage($this->config->getInquiryAssetExpanderPageNumber())
            ->setMaxPerPage($this->config->getInquiryAssetExpanderMaxPerPage());

        return $this->sspInquiryReader->getSspInquiryCollection(
            (new SspInquiryCriteriaTransfer())
                ->addSort((new SortTransfer())->setIsAscending(false)->setField(static::DEFAULT_SORT_FIELD))
                ->setSspInquiryConditions(
                    (new SspInquiryConditionsTransfer())
                        ->setSspAssetIds($sspAssetIds)
                        ->setSspInquiryOwnerConditionGroup($sspInquiryOwnerConditionGroupTransfer),
                )
                ->setPagination($paginationTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer $sspAssetCollectionTransfer
     * @param \Generated\Shared\Transfer\SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    protected function expandAssetsWithInquiries(
        SspAssetCollectionTransfer $sspAssetCollectionTransfer,
        SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
    ): SspAssetCollectionTransfer {
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
