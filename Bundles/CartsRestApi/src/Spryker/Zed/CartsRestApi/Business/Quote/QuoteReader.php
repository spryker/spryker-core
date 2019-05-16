<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionChecker;
use Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;

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
     * @var \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected $quoteCollectionReaderPlugin;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface
     */
    protected $quoteMapper;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionChecker
     */
    protected $quotePermissionChecker;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface $quoteCollectionReaderPlugin
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface $quoteMapper
     * @param \Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionChecker $quotePermissionChecker
     */
    public function __construct(
        CartsRestApiToQuoteFacadeInterface $quoteFacade,
        CartsRestApiToStoreFacadeInterface $storeFacade,
        QuoteCollectionReaderPluginInterface $quoteCollectionReaderPlugin,
        QuoteMapperInterface $quoteMapper,
        QuotePermissionChecker $quotePermissionChecker
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->storeFacade = $storeFacade;
        $this->quoteCollectionReaderPlugin = $quoteCollectionReaderPlugin;
        $this->quoteMapper = $quoteMapper;
        $this->quotePermissionChecker = $quotePermissionChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByUuid(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireUuid();

        $quoteResponseTransfer = $this->quoteFacade->findQuoteByUuid($quoteTransfer);
        $quoteTransfer->setIdQuote($quoteResponseTransfer->getQuoteTransfer()->getIdQuote());

        if (!$this->quotePermissionChecker->checkQuoteReadPermission($quoteTransfer)) {
            return $quoteResponseTransfer
                ->setIsSuccessful(false)
                ->addErrorCode(CartsRestApiSharedConfig::RESPONSE_CODE_UNAUTHORIZED_ACTION);
        }

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())->setMessage(CartsRestApiSharedConfig::RESPONSE_CODE_CART_NOT_FOUND));

            return $this->quoteMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionResponseTransfer
     */
    public function getQuoteCollectionByCustomerReference(
        CustomerTransfer $customerTransfer
    ): QuoteCollectionResponseTransfer {
        return $this->quoteCollectionReaderPlugin->getQuoteCollection($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionResponseTransfer
     */
    public function getQuoteCollectionByCustomerAndStore(
        CustomerTransfer $customerTransfer
    ): QuoteCollectionResponseTransfer {
        $quoteCollectionResponseTransfer = new QuoteCollectionResponseTransfer();
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $quoteResponseTransfer = $this->quoteFacade->findQuoteByCustomerAndStore($customerTransfer, $storeTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->quoteMapper->mapQuoteResponseErrorsToRestQuoteCollectionResponseErrors(
                $quoteResponseTransfer
            );
        }

        return $quoteCollectionResponseTransfer
            ->setQuoteCollection((new QuoteCollectionTransfer())->addQuote($quoteResponseTransfer->getQuoteTransfer()));
    }
}
