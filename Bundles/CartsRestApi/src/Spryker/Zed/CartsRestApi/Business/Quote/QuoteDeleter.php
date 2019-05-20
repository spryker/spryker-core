<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface;

class QuoteDeleter implements QuoteDeleterInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface
     */
    protected $quoteMapper;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface
     */
    protected $quotePermissionChecker;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface $quoteMapper
     * @param \Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface $quotePermissionChecker
     */
    public function __construct(
        CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade,
        QuoteReaderInterface $quoteReader,
        QuoteMapperInterface $quoteMapper,
        QuotePermissionCheckerInterface $quotePermissionChecker
    ) {
        $this->persistentCartFacade = $persistentCartFacade;
        $this->quoteReader = $quoteReader;
        $this->quoteMapper = $quoteMapper;
        $this->quotePermissionChecker = $quotePermissionChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireCustomer();

        if (!$quoteTransfer->getUuid()) {
            $quoteResponseTransfer = (new QuoteResponseTransfer())
                ->addError((new QuoteErrorTransfer())->setMessage(CartsRestApiSharedConfig::RESPONSE_CODE_CART_ID_MISSING));

            return $this->quoteMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        $quoteResponseTransfer = $this->quoteReader->findQuoteByUuid($quoteTransfer);
        if ($quoteResponseTransfer->getIsSuccessful() === false) {
            return $this->quoteMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        $quoteTransfer->setIdQuote($quoteResponseTransfer->getQuoteTransfer()->getIdQuote());

        if (!$this->quotePermissionChecker->checkQuoteWritePermission($quoteTransfer)) {
            return $quoteResponseTransfer
                ->addErrorCode(CartsRestApiSharedConfig::RESPONSE_CODE_UNAUTHORIZED_ACTION);
        }

        $quoteResponseTransfer = $this->persistentCartFacade->deleteQuote(
            $quoteResponseTransfer->getQuoteTransfer()
            ->setCustomer($quoteTransfer->getCustomer())
        );

        if ($quoteResponseTransfer->getIsSuccessful() === false) {
            return $this->quoteMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        return $quoteResponseTransfer;
    }
}
