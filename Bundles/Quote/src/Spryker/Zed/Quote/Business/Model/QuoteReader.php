<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Model;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface;
use Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteExpanderPluginInterface[]
     */
    protected $quoteExpanderPlugins;

    /**
     * @param \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface $quoteRepository
     * @param \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteExpanderPluginInterface[] $quoteExpanderPlugins
     * @param \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        QuoteRepositoryInterface $quoteRepository,
        array $quoteExpanderPlugins,
        QuoteToStoreFacadeInterface $storeFacade
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->quoteExpanderPlugins = $quoteExpanderPlugins;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @deprecated Use findQuoteByCustomerAndStore() instead.
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByCustomer(CustomerTransfer $customerTransfer): QuoteResponseTransfer
    {
        $customerTransfer->requireCustomerReference();
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setIsSuccessful(false);
        $quoteTransfer = $this->quoteRepository->findQuoteByCustomerReferenceAndIdStore(
            $customerTransfer->getCustomerReference(),
            $this->storeFacade->getCurrentStore()->getIdStore()
        );

        $quoteTransfer = $this->executeExpandQuotePlugins($quoteTransfer);

        $quoteResponseTransfer = $this->setQuoteResponseTransfer($quoteResponseTransfer, $quoteTransfer);
        if ($quoteTransfer) {
            $quoteResponseTransfer->setCustomer($customerTransfer);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByCustomerAndStore(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteResponseTransfer
    {
        $customerTransfer->requireCustomerReference();
        $storeTransfer->requireIdStore();
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setIsSuccessful(false);
        $quoteTransfer = $this->quoteRepository
            ->findQuoteByCustomerReferenceAndIdStore(
                $customerTransfer->getCustomerReference(),
                $storeTransfer->getIdStore()
            );

        $quoteTransfer = $this->executeExpandQuotePlugins($quoteTransfer);

        $quoteResponseTransfer = $this->setQuoteResponseTransfer($quoteResponseTransfer, $quoteTransfer);
        if ($quoteTransfer) {
            $quoteResponseTransfer->setCustomer($customerTransfer);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteById($idQuote): QuoteResponseTransfer
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setIsSuccessful(false);
        $quoteTransfer = $this->quoteRepository
            ->findQuoteById($idQuote);

        $quoteTransfer = $this->executeExpandQuotePlugins($quoteTransfer);

        return $this->setQuoteResponseTransfer($quoteResponseTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function setQuoteResponseTransfer(QuoteResponseTransfer $quoteResponseTransfer, ?QuoteTransfer $quoteTransfer = null): QuoteResponseTransfer
    {
        if (!$quoteTransfer) {
            $quoteResponseTransfer->setIsSuccessful(false);

            return $quoteResponseTransfer;
        }

        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
        $quoteResponseTransfer->setIsSuccessful(true);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByUuid(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireUuid();
        $quoteResponseTransfer = (new QuoteResponseTransfer())
            ->setIsSuccessful(false);
        $quoteTransfer = $this->quoteRepository
            ->findQuoteByUuid($quoteTransfer->getUuid());

        if (!$quoteTransfer) {
            return $quoteResponseTransfer;
        }

        $quoteTransfer = $this->executeExpandQuotePlugins($quoteTransfer);

        return $quoteResponseTransfer
            ->setQuoteTransfer($quoteTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getFilteredQuoteCollection(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        $quoteCollectionTransfer = $this->quoteRepository->filterQuoteCollection($quoteCriteriaFilterTransfer);
        $quoteCollectionTransfer = $this->executeExpandQuotePluginsForQuoteCollection($quoteCollectionTransfer);

        return $quoteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function executeExpandQuotePluginsForQuoteCollection(QuoteCollectionTransfer $quoteCollectionTransfer): QuoteCollectionTransfer
    {
        $expandedQuotesCollection = new QuoteCollectionTransfer();

        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            $expandedQuotesCollection->addQuote(
                $this->executeExpandQuotePlugins($quoteTransfer)
            );
        }

        return $expandedQuotesCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function executeExpandQuotePlugins(?QuoteTransfer $quoteTransfer): ?QuoteTransfer
    {
        if (!$quoteTransfer) {
            return null;
        }

        foreach ($this->quoteExpanderPlugins as $quoteExpanderPlugin) {
            $quoteTransfer = $quoteExpanderPlugin->expand($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
