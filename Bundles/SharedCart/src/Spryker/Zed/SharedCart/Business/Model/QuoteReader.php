<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\Model;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToQuoteFacadeInterface;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected $sharedCartRepository;

    /**
     * @var \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     * @param \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(SharedCartRepositoryInterface $sharedCartRepository, SharedCartToQuoteFacadeInterface $quoteFacade)
    {
        $this->sharedCartRepository = $sharedCartRepository;
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function findCustomerSharedQuotes(CompanyUserTransfer $companyUserTransfer): QuoteCollectionTransfer
    {
        $quoteIds = $this->sharedCartRepository->findQuoteIdCollectionByIdCompanyUser(
            $companyUserTransfer->getIdCompanyUser()
        );

        $quoteCriteriaFilterTransfer = new QuoteCriteriaFilterTransfer();
        $quoteCriteriaFilterTransfer->setQuoteIds($quoteIds);

        return $this->applyIsDefaultFlagForSharedQuotes(
            $this->quoteFacade->getQuoteCollection($quoteCriteriaFilterTransfer),
            $companyUserTransfer->getIdCompanyUser()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function applyIsDefaultFlagForSharedQuotes(QuoteCollectionTransfer $quoteCollectionTransfer, int $idCompanyUser): QuoteCollectionTransfer
    {
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            $isDefault = $this->sharedCartRepository->getIsDefaultFlagForSharedCart(
                $quoteTransfer->getIdQuote(),
                $idCompanyUser
            );

            $quoteTransfer->setIsDefault($isDefault);
        }

        return $quoteCollectionTransfer;
    }
}
