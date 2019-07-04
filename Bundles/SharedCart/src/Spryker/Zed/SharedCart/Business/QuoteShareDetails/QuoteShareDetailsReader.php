<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\QuoteShareDetails;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Generated\Shared\Transfer\ShareDetailCriteriaFilterTransfer;
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCustomerFacadeInterface;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

class QuoteShareDetailsReader implements QuoteShareDetailsReaderInterface
{
    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected $sharedCartRepository;

    /**
     * @var \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     * @param \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCustomerFacadeInterface $customerFacade
     */
    public function __construct(SharedCartRepositoryInterface $sharedCartRepository, SharedCartToCustomerFacadeInterface $customerFacade)
    {
        $this->sharedCartRepository = $sharedCartRepository;
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getShareDetailsByIdQuote(QuoteTransfer $quoteTransfer): ShareDetailCollectionTransfer
    {
        $quoteTransfer->requireIdQuote();

        return $this->sharedCartRepository->findShareDetailsByQuoteId($quoteTransfer->getIdQuote());
    }

    /**
     * @param \Generated\Shared\Transfer\ShareDetailCriteriaFilterTransfer $shareDetailCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getShareDetailCollectionByShareDetailCriteria(ShareDetailCriteriaFilterTransfer $shareDetailCriteriaFilterTransfer): ShareDetailCollectionTransfer
    {
        return $this->sharedCartRepository->getShareDetailCollectionByShareDetailCriteria($shareDetailCriteriaFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollectionByQuote(QuoteTransfer $quoteTransfer): CustomerCollectionTransfer
    {
        $this->assertQuoteCustomerData($quoteTransfer);

        $customerCollectionTransfer = $this->sharedCartRepository->getCustomerCollectionByQuote($quoteTransfer->getIdQuote());

        if ($this->isQuoteOwner($quoteTransfer, $quoteTransfer->getCustomer())) {
            return $customerCollectionTransfer;
        }

        $quoteOwnerCustomerResponseTransfer = $this->customerFacade->findCustomerByReference($quoteTransfer->getCustomerReference());
        if ($quoteOwnerCustomerResponseTransfer->getIsSuccess()) {
            $customerCollectionTransfer->addCustomer($quoteOwnerCustomerResponseTransfer->getCustomerTransfer());
        }

        return $customerCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertQuoteCustomerData(QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer->requireIdQuote()
            ->requireCustomerReference()
            ->requireCustomer()
            ->getCustomer()
            ->requireCustomerReference();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isQuoteOwner(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        return $quoteTransfer->getCustomerReference() === $customerTransfer->getCustomerReference();
    }
}
