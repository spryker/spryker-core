<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\QuoteItem;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface;

class QuoteItemUpdater implements QuoteItemUpdaterInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemReaderInterface
     */
    protected $quoteItemReader;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface
     */
    protected $quotePermissionChecker;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemReaderInterface $quoteItemReader
     * @param \Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface $quotePermissionChecker
     */
    public function __construct(
        CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade,
        QuoteItemReaderInterface $quoteItemReader,
        QuotePermissionCheckerInterface $quotePermissionChecker
    ) {
        $this->persistentCartFacade = $persistentCartFacade;
        $this->quoteItemReader = $quoteItemReader;
        $this->quotePermissionChecker = $quotePermissionChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function changeItemQuantity(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): QuoteResponseTransfer
    {
        $restCartItemsAttributesTransfer
            ->requireQuoteUuid()
            ->requireSku()
            ->requireCustomerReference()
            ->requireQuantity();

        $quoteResponseTransfer = $this->quoteItemReader->readQuoteItem($restCartItemsAttributesTransfer);

        if (!$this->quotePermissionChecker->checkQuoteWritePermission($quoteResponseTransfer->getQuoteTransfer())) {
            return $quoteResponseTransfer
                ->setIsSuccessful(false)
                ->addError((new QuoteErrorTransfer())
                    ->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_UNAUTHORIZED_CART_ACTION));
        }

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $persistentCartChangeQuantityTransfer = $this->createPersistentCartChangeQuantityTransfer(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restCartItemsAttributesTransfer
        );

        $quoteResponseTransfer = $this->persistentCartFacade->changeItemQuantity($persistentCartChangeQuantityTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())
                    ->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_UPDATING_CART_ITEM));
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer
     */
    protected function createPersistentCartChangeQuantityTransfer(
        QuoteTransfer $quoteTransfer,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): PersistentCartChangeQuantityTransfer {
        $customerTransfer = $restCartItemsAttributesTransfer->getCustomer() ?? new CustomerTransfer();
        $customerTransfer->setCustomerReference($restCartItemsAttributesTransfer->getCustomerReference());

        return (new PersistentCartChangeQuantityTransfer())
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setItem((new ItemTransfer())
                ->setSku($restCartItemsAttributesTransfer->getSku())
                ->setQuantity($restCartItemsAttributesTransfer->getQuantity()))
            ->setCustomer($customerTransfer);
    }
}
