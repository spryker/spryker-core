<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryIncludeTransfer;
use Generated\Shared\Transfer\SspInquiryOwnerConditionGroupTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Client\Store\StoreClientInterface;
use SprykerFeature\Client\SspInquiryManagement\SspInquiryManagementClientInterface;
use SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementConfig;
use Symfony\Component\HttpFoundation\Request;

class SspInquiryReader implements SspInquiryReaderInterface
{
    /**
     * @var int
     */
    protected const DEFAULT_PAGE = 1;

    /**
     * @param \SprykerFeature\Client\SspInquiryManagement\SspInquiryManagementClientInterface $sspInquiryManagementClient
     * @param \SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementConfig $sspInquiryManagementConfig
     * @param \Spryker\Client\Store\StoreClientInterface $storeClient
     */
    public function __construct(
        protected SspInquiryManagementClientInterface $sspInquiryManagementClient,
        protected SspInquiryManagementConfig $sspInquiryManagementConfig,
        protected StoreClientInterface $storeClient
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryCollection(Request $request, SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer
    {
        $sspInquiryCriteriaTransfer->setPagination($this->createPaginationTransfer($request));

        if (!$sspInquiryCriteriaTransfer->getSspInquiryConditions()) {
              $sspInquiryCriteriaTransfer->setSspInquiryConditions(new SspInquiryConditionsTransfer());
        }

        return $this->sspInquiryManagementClient->getSspInquiryCollection($sspInquiryCriteriaTransfer);
    }

    /**
     * @param string $reference
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer|null
     */
    public function getSspInquiry(string $reference, CompanyUserTransfer $companyUserTransfer): ?SspInquiryTransfer
    {
          $sspInquiryCollectionResponseTransfer = $this->sspInquiryManagementClient->getSspInquiryCollection(
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function createPaginationTransfer(Request $request): PaginationTransfer
    {
        $paginationTransfer = new PaginationTransfer();

        $paginationTransfer->setPage(
            $request->query->getInt($this->sspInquiryManagementConfig->getParamPage(), static::DEFAULT_PAGE),
        );
        $paginationTransfer->setMaxPerPage(
            $request->query->getInt($this->sspInquiryManagementConfig->getParamPerPage(), $this->sspInquiryManagementConfig->getSspInquiryCountPerPageList()),
        );

        return $paginationTransfer;
    }
}
