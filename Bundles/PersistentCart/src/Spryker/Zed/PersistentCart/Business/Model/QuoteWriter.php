<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;

class QuoteWriter implements QuoteWriterInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface
     */
    protected $quoteResponseExpander;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperationInterface
     */
    protected $quoteItemOperation;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface
     */
    protected $quoteResolver;

    /**
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface $quoteResponseExpander
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface $quoteResolver
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperationInterface $quoteItemOperation
     */
    public function __construct(
        PersistentCartToQuoteFacadeInterface $quoteFacade,
        QuoteResponseExpanderInterface $quoteResponseExpander,
        QuoteResolverInterface $quoteResolver,
        QuoteItemOperationInterface $quoteItemOperation
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->quoteResponseExpander = $quoteResponseExpander;
        $this->quoteItemOperation = $quoteItemOperation;
        $this->quoteResolver = $quoteResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference());

        return $this->quoteResponseExpander->expand($this->quoteFacade->createQuote($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuoteWithReloadedItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->quoteFacade->createQuote($quoteTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $quoteResponseTransfer = $this->quoteItemOperation->reloadItems($quoteResponseTransfer->getQuoteTransfer());
        }

        return $this->quoteResponseExpander->expand($quoteResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->quoteResolver->resolveCustomerQuote(
            $quoteUpdateRequestTransfer->getIdQuote(),
            $quoteUpdateRequestTransfer->getCustomer()
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->quoteResponseExpander->expand($quoteResponseTransfer);
        }
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $quoteTransfer->fromArray($quoteUpdateRequestTransfer->getQuoteUpdateRequestAttributes()->modifiedToArray(), true);

        return $this->quoteResponseExpander->expand($this->quoteFacade->updateQuote($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateAndReloadQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->quoteResolver->resolveCustomerQuote(
            $quoteUpdateRequestTransfer->getIdQuote(),
            $quoteUpdateRequestTransfer->getCustomer()
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->quoteResponseExpander->expand($quoteResponseTransfer);
        }

        if (!$this->isQuoteOwner($quoteResponseTransfer->getQuoteTransfer()) && !$this->hasCustomerWritePermission($quoteUpdateRequestTransfer)) {
            $quoteResponseTransfer = (new QuoteResponseTransfer())
                ->setCustomer($quoteResponseTransfer->getCustomer())
                ->setIsSuccessful(false);

            return $this->quoteResponseExpander->expand($quoteResponseTransfer);
        }

        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $quoteTransfer->fromArray($quoteUpdateRequestTransfer->getQuoteUpdateRequestAttributes()->modifiedToArray(), true);

        return $this->quoteItemOperation->reloadItems($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceQuoteByCustomerAndStore(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->quoteFacade->findQuoteByCustomerAndStore(
            $quoteTransfer->getCustomer(),
            $quoteTransfer->getStore()
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteTransfer->setIdQuote($quoteResponseTransfer->getQuoteTransfer()->getIdQuote());

        return $this->quoteFacade->updateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer
     *
     * @return bool
     */
    protected function hasCustomerWritePermission(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): bool
    {
        $companyUserId = $this->findCompanyUserId($quoteUpdateRequestTransfer);

        if (!$companyUserId) {
            return true;
        }

        return $this->can('WriteSharedCartPermissionPlugin', $companyUserId, $quoteUpdateRequestTransfer->getIdQuote());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer
     *
     * @return int|null
     */
    protected function findCompanyUserId(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): ?int
    {
        $companyUserTransfer = $quoteUpdateRequestTransfer
            ->getCustomer()
            ->getCompanyUserTransfer();

        return $companyUserTransfer ? $companyUserTransfer->getIdCompanyUser() : null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteOwner(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->requireCustomer()
                ->getCustomer()
                ->getCustomerReference() === $quoteTransfer->getCustomerReference();
    }
}
