<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Inquiry\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryIncludeTransfer;
use Generated\Shared\Transfer\SspInquiryOwnerConditionGroupTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Client\Store\StoreClientInterface;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;
use Symfony\Component\HttpFoundation\Request;

class SspInquiryReader implements SspInquiryReaderInterface
{
    /**
     * @var int
     */
    protected const DEFAULT_PAGE = 1;

    public function __construct(
        protected SelfServicePortalClientInterface $selfServicePortalClient,
        protected SelfServicePortalConfig $selfServicePortalConfig,
        protected StoreClientInterface $storeClient
    ) {
    }

    public function getSspInquiryCollection(Request $request, SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer
    {
        $sspInquiryCriteriaTransfer->setPagination($this->createPaginationTransfer($request));

        if (!$sspInquiryCriteriaTransfer->getSspInquiryConditions()) {
            $sspInquiryCriteriaTransfer->setSspInquiryConditions(new SspInquiryConditionsTransfer());
        }

        return $this->selfServicePortalClient->getSspInquiryCollection($sspInquiryCriteriaTransfer);
    }

    public function getSspInquiry(string $reference, CompanyUserTransfer $companyUserTransfer): ?SspInquiryTransfer
    {
        $sspInquiryCollectionResponseTransfer = $this->selfServicePortalClient->getSspInquiryCollection(
            (new SspInquiryCriteriaTransfer())->setSspInquiryConditions(
                (new SspInquiryConditionsTransfer())
                    ->addReference($reference)
                    ->setSspInquiryOwnerConditionGroup(
                        (new SspInquiryOwnerConditionGroupTransfer())
                            ->setIdCompanyBusinessUnit($companyUserTransfer->getFkCompanyBusinessUnit())
                            ->setIdCompany($companyUserTransfer->getFkCompany()),
                    ),
            )
                ->setInclude(
                    (new SspInquiryIncludeTransfer())
                        ->setWithCompanyUser(true)
                        ->setWithFiles(true)
                        ->setWithOrder(true)
                        ->setWithManualEvents(true)
                        ->setWithSspAsset(true),
                ),
        );

        if ($sspInquiryCollectionResponseTransfer->getSspInquiries()->count() === 0) {
            return null;
        }

        return $sspInquiryCollectionResponseTransfer->getSspInquiries()->getIterator()->current();
    }

    protected function createPaginationTransfer(Request $request): PaginationTransfer
    {
        $paginationTransfer = new PaginationTransfer();

        $paginationTransfer->setPage(
            $request->query->getInt($this->selfServicePortalConfig->getSspInquiryParamPage(), static::DEFAULT_PAGE),
        );
        $paginationTransfer->setMaxPerPage(
            $request->query->getInt($this->selfServicePortalConfig->getSspInquiryParamPerPage(), $this->selfServicePortalConfig->getSspInquiryCountPerPageList()),
        );

        return $paginationTransfer;
    }
}
