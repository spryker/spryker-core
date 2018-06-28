<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\QuoteResponseExpander;

use ArrayObject;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

class QuoteShareDetailsQuoteResponseExpander implements QuoteResponseExpanderInterface
{
    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected $sharedCartRepository;

    /**
     * QuoteReader constructor.
     *
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     */
    public function __construct(SharedCartRepositoryInterface $sharedCartRepository)
    {
        $this->sharedCartRepository = $sharedCartRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function expand(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        $customerTransfer = $quoteResponseTransfer->requireCustomer()->getCustomer();
        if (!$quoteResponseTransfer->getCustomerQuotes() || !count($quoteResponseTransfer->getCustomerQuotes()->getQuotes())) {
            return $quoteResponseTransfer;
        }
        $companyUserTransferCollection = $this->sharedCartRepository->findShareInformationCustomer(
            $customerTransfer->getCustomerReference()
        );
        if (count($companyUserTransferCollection)) {
            return $this->addShareInformation($quoteResponseTransfer, $companyUserTransferCollection);
        }
        if ($quoteResponseTransfer->getQuoteTransfer() && $this->isCustomerQuoteOwner($quoteResponseTransfer->getQuoteTransfer())) {
            $quoteResponseTransfer->getQuoteTransfer()->setShareDetails(new ArrayObject());
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer[] $companyUserTransferCollection
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function addShareInformation(QuoteResponseTransfer $quoteResponseTransfer, array $companyUserTransferCollection): QuoteResponseTransfer
    {
        $quotePermissionGroupTransferList = $this->getQuotePermissionGroupList();
        $quotePermissionGroupTransferList = $this->indexQuotePermissionGroups($quotePermissionGroupTransferList);
        $groupedCompanyUserTransferCollection = $this->groupCompanyUsersByQuoteId($companyUserTransferCollection);
        foreach ($quoteResponseTransfer->getCustomerQuotes()->getQuotes() as $quoteTransfer) {
            if (!empty($groupedCompanyUserTransferCollection[$quoteTransfer->getIdQuote()])) {
                $quoteTransfer->setShareDetails(
                    $this->createShareDetails(
                        $quoteTransfer->getIdQuote(),
                        $groupedCompanyUserTransferCollection[$quoteTransfer->getIdQuote()],
                        $quotePermissionGroupTransferList
                    )
                );
            }
        }
        if ($quoteResponseTransfer->getQuoteTransfer() && !empty($groupedCompanyUserTransferCollection[$quoteResponseTransfer->getQuoteTransfer()->getIdQuote()])) {
            $quoteResponseTransfer->getQuoteTransfer()->setShareDetails(
                $this->createShareDetails(
                    $quoteResponseTransfer->getQuoteTransfer()->getIdQuote(),
                    $groupedCompanyUserTransferCollection[$quoteResponseTransfer->getQuoteTransfer()->getIdQuote()],
                    $quotePermissionGroupTransferList
                )
            );
        }

        return $quoteResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer[]
     */
    protected function getQuotePermissionGroupList(): array
    {
        $criteriaFilterTransfer = new QuotePermissionGroupCriteriaFilterTransfer();
        $criteriaFilterTransfer->setFilter(new FilterTransfer());

        return $this->sharedCartRepository->findQuotePermissionGroupList($criteriaFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer[] $companyUserTransferCollection
     *
     * @return \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer[][]
     */
    protected function groupCompanyUsersByQuoteId(array $companyUserTransferCollection): array
    {
        $groupedCompanyUserTransferCollection = [];
        foreach ($companyUserTransferCollection as $companyUserTransfer) {
            foreach ($companyUserTransfer->getSpyQuoteCompanyUsers() as $quoteCompanyUser) {
                $idQuote = $quoteCompanyUser->getFkQuote();
                $groupedCompanyUserTransferCollection[$idQuote][] = $companyUserTransfer;
            }
        }

        return $groupedCompanyUserTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer[] $quotePermissionGroupTransferList
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer[]
     */
    protected function indexQuotePermissionGroups(array $quotePermissionGroupTransferList): array
    {
        $indexedQuotePermissionTransferList = [];
        foreach ($quotePermissionGroupTransferList as $quotePermissionGroupTransfer) {
            $indexedQuotePermissionTransferList[$quotePermissionGroupTransfer->getIdQuotePermissionGroup()] = $quotePermissionGroupTransfer;
        }

        return $indexedQuotePermissionTransferList;
    }

    /**
     * @param int $idQuote
     * @param \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer[] $companyUserTransferCollection
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer[] $quotePermissionGroupTransferList
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShareDetailTransfer[]
     */
    protected function createShareDetails(int $idQuote, array $companyUserTransferCollection, array $quotePermissionGroupTransferList): ArrayObject
    {
        $shareDetailsTransferList = new ArrayObject();
        foreach ($companyUserTransferCollection as $companyUserEntityTransfer) {
            foreach ($companyUserEntityTransfer->getSpyQuoteCompanyUsers() as $quoteCompanyUser) {
                if ($idQuote !== $quoteCompanyUser->getFkQuote()) {
                    continue;
                }
                $shareDetailTransfer = new ShareDetailTransfer();
                $shareDetailTransfer
                    ->setIdQuoteCompanyUser($quoteCompanyUser->getIdQuoteCompanyUser())
                    ->setIdCompanyUser($companyUserEntityTransfer->getIdCompanyUser());

                $customerTransfer = $companyUserEntityTransfer->getCustomer();
                $shareDetailTransfer->setCustomerName(
                    $customerTransfer->getLastName() . ' ' . $customerTransfer->getFirstName()
                );

                $shareDetailTransfer->setQuotePermissionGroup(
                    $quotePermissionGroupTransferList[$quoteCompanyUser->getFkQuotePermissionGroup()]
                );

                $shareDetailsTransferList->append($shareDetailTransfer);
            }
        }

        return $shareDetailsTransferList;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isCustomerQuoteOwner(QuoteTransfer $quoteTransfer): bool
    {
        $customer = $quoteTransfer->getCustomer();
        return strcmp($customer->getCustomerReference(), $quoteTransfer->getCustomerReference()) === 0;
    }
}
