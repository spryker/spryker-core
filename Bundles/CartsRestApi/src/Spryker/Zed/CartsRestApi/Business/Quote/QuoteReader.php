<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface
     */
    protected $quotePermissionChecker;

    /**
     * @var \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionExpanderPluginInterface[]
     */
    protected $quoteCollectionExpanderPlugins;

    /**
     * @var \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteExpanderPluginInterface[]
     */
    protected $quoteExpanderPlugins;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface $quotePermissionChecker
     * @param \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionExpanderPluginInterface[] $quoteCollectionExpanderPlugins
     * @param \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteExpanderPluginInterface[] $quoteExpanderPlugins
     */
    public function __construct(
        CartsRestApiToQuoteFacadeInterface $quoteFacade,
        CartsRestApiToStoreFacadeInterface $storeFacade,
        QuotePermissionCheckerInterface $quotePermissionChecker,
        array $quoteCollectionExpanderPlugins,
        array $quoteExpanderPlugins
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->storeFacade = $storeFacade;
        $this->quotePermissionChecker = $quotePermissionChecker;
        $this->quoteCollectionExpanderPlugins = $quoteCollectionExpanderPlugins;
        $this->quoteExpanderPlugins = $quoteExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByUuid(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireUuid();
        $quoteTransfer->requireCustomerReference();

        $quoteResponseTransfer = $this->quoteFacade->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_CART_NOT_FOUND));
        }
        $quoteResponseTransfer->getQuoteTransfer()->setCustomer($quoteTransfer->getCustomer());

        if (!$this->quotePermissionChecker->checkQuoteReadPermission($quoteResponseTransfer->getQuoteTransfer())) {
            return $quoteResponseTransfer
                ->setIsSuccessful(false)
                ->addError((new QuoteErrorTransfer())
                    ->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_CART_NOT_FOUND));
        }

        $expandedQuoteTransfer = $this->executeQuoteExpanderPlugins($quoteResponseTransfer->getQuoteTransfer());

        return $quoteResponseTransfer->setQuoteTransfer($expandedQuoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $quoteCriteriaFilterTransfer->setIdStore($storeTransfer->getIdStore());

        $quoteCollectionTransfer = $this->quoteFacade->getQuoteCollection($quoteCriteriaFilterTransfer);

        return $this->executeQuoteCollectionExpanderPlugins(
            $quoteCriteriaFilterTransfer,
            $quoteCollectionTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function executeQuoteCollectionExpanderPlugins(
        QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer,
        QuoteCollectionTransfer $quoteCollectionTransfer
    ): QuoteCollectionTransfer {
        foreach ($this->quoteCollectionExpanderPlugins as $quoteCollectionExpanderPlugin) {
            $quoteCollectionTransfer = $quoteCollectionExpanderPlugin->expandQuoteCollection(
                $quoteCollectionTransfer,
                $quoteCriteriaFilterTransfer
            );
        }

        return $quoteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeQuoteExpanderPlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($this->quoteExpanderPlugins as $quoteExpanderPlugin) {
            $quoteTransfer = $quoteExpanderPlugin->expandQuote($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
