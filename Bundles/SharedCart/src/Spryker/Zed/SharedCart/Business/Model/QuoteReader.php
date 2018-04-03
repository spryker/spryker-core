<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\Model;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyQuoteEntityTransfer;
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
        $quoteEntityTransferList = $this->sharedCartRepository->findQuotesByIdCompanyUser(
            $companyUserTransfer->getIdCompanyUser()
        );

        return $this->mapQuoteCollectionTransfer($quoteEntityTransferList);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuoteEntityTransfer[] $quoteEntityTransferList
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function mapQuoteCollectionTransfer(array $quoteEntityTransferList): QuoteCollectionTransfer
    {
        $quoteCollectionTransfer = new QuoteCollectionTransfer();
        foreach ($quoteEntityTransferList as $quoteEntityTransfer) {
            $quoteCollectionTransfer->addQuote($this->mapQuoteTransfer($quoteEntityTransfer));
        }

        return $quoteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuoteEntityTransfer $quoteEntityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapQuoteTransfer(SpyQuoteEntityTransfer $quoteEntityTransfer): QuoteTransfer
    {
        return $this->quoteFacade->mapQuoteTransfer($quoteEntityTransfer);
    }
}
