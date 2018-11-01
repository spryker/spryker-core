<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\CartSharer;

use ArrayObject;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Client\Kernel\PermissionAwareTrait;
use Spryker\Client\SharedCart\Dependency\Client\SharedCartToCustomerClientInterface;
use Spryker\Client\SharedCart\Dependency\Client\SharedCartToMessengerClientInterface;
use Spryker\Client\SharedCart\Dependency\Client\SharedCartToMultiCartClientInterface;
use Spryker\Client\SharedCart\Dependency\Client\SharedCartToPersistentCartClientInterface;
use Spryker\Client\SharedCart\Exception\CartNotFoundException;
use Spryker\Client\SharedCart\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Client\SharedCart\Zed\SharedCartStubInterface;

class CartSharer implements CartSharerInterface
{
    use PermissionAwareTrait;

    public const GLOSSARY_KEY_SHARED_CART_SHARE_ERROR_ALREADY_EXIST = 'shared_cart.share.error.already_exist';

    /**
     * @var \Spryker\Client\SharedCart\Dependency\Client\SharedCartToMultiCartClientInterface
     */
    protected $multiCartClient;

    /**
     * @var \Spryker\Client\SharedCart\Dependency\Client\SharedCartToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @var \Spryker\Client\SharedCart\Zed\SharedCartStubInterface
     */
    protected $sharedCartStub;

    /**
     * @var \Spryker\Client\SharedCart\Dependency\Client\SharedCartToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @var \Spryker\Client\SharedCart\Dependency\Client\SharedCartToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\SharedCart\Zed\SharedCartStubInterface $sharedCartStub
     * @param \Spryker\Client\SharedCart\Dependency\Client\SharedCartToMultiCartClientInterface $multiCartClient
     * @param \Spryker\Client\SharedCart\Dependency\Client\SharedCartToPersistentCartClientInterface $persistentCartClient
     * @param \Spryker\Client\SharedCart\Dependency\Client\SharedCartToMessengerClientInterface $messengerClient
     * @param \Spryker\Client\SharedCart\Dependency\Client\SharedCartToCustomerClientInterface $customerClient
     */
    public function __construct(
        SharedCartStubInterface $sharedCartStub,
        SharedCartToMultiCartClientInterface $multiCartClient,
        SharedCartToPersistentCartClientInterface $persistentCartClient,
        SharedCartToMessengerClientInterface $messengerClient,
        SharedCartToCustomerClientInterface $customerClient
    ) {
        $this->multiCartClient = $multiCartClient;
        $this->persistentCartClient = $persistentCartClient;
        $this->sharedCartStub = $sharedCartStub;
        $this->messengerClient = $messengerClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @deprecated Please use CartSharerInterface::updateQuotePermissions() instead
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addShareCart(ShareCartRequestTransfer $shareCartRequestTransfer): QuoteResponseTransfer
    {
        $quoteTransfer = $this->getQuote($shareCartRequestTransfer->getIdQuote());
        if (!$this->validateShareCartRequest($shareCartRequestTransfer, $quoteTransfer)) {
            $this->messengerClient->addErrorMessage(self::GLOSSARY_KEY_SHARED_CART_SHARE_ERROR_ALREADY_EXIST);
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteResponseTransfer->setIsSuccessful(false);

            return $quoteResponseTransfer;
        }
        $quoteTransfer->addShareDetail($this->createShareCartDetail($shareCartRequestTransfer));
        $quoteUpdateRequestTransfer = $this->createQuoteUpdateRequest($quoteTransfer);
        $quoteUpdateRequestTransfer->getQuoteUpdateRequestAttributes()->setShareDetails($quoteTransfer->getShareDetails());

        return $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);
    }

    /**
     * @deprecated Please use CartSharerInterface::updateQuotePermissions() instead
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeShareCart(ShareCartRequestTransfer $shareCartRequestTransfer): QuoteResponseTransfer
    {
        $quoteTransfer = $this->getQuote($shareCartRequestTransfer->getIdQuote());
        $quoteUpdateRequestTransfer = $this->createQuoteUpdateRequest($quoteTransfer);
        $quoteUpdateRequestTransfer->getQuoteUpdateRequestAttributes()
            ->setShareDetails(
                $this->filterShareCartToRemove(
                    $quoteTransfer->getShareDetails(),
                    $shareCartRequestTransfer
                )
            );

        return $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function dismissSharedCart(ShareCartRequestTransfer $shareCartRequestTransfer): QuoteResponseTransfer
    {
        if (!$this->can(ReadSharedCartPermissionPlugin::KEY, $shareCartRequestTransfer->getIdQuote())) {
            return (new QuoteResponseTransfer())->setIsSuccessful(false);
        }
        $quoteTransfer = $this->getQuote($shareCartRequestTransfer->getIdQuote());
        $quoteUpdateRequestTransfer = $this->createQuoteUpdateRequest($quoteTransfer);
        $quoteUpdateRequestTransfer->getQuoteUpdateRequestAttributes()
            ->setShareDetails(
                $this->filterShareCartToRemove(
                    $this->sharedCartStub->getShareDetailsByIdQuoteAction($quoteTransfer)->getShareDetails(),
                    $shareCartRequestTransfer
                )
            );

        return $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuotePermissions(ShareCartRequestTransfer $shareCartRequestTransfer): QuoteResponseTransfer
    {
        $quoteTransfer = $this->getQuote($shareCartRequestTransfer->getIdQuote());
        $quoteTransfer = $this->updateQuoteShareDetailsAccordinglyToShareCartRequest($shareCartRequestTransfer, $quoteTransfer);
        $quoteUpdateRequestTransfer = $this->createQuoteUpdateRequest($quoteTransfer);
        $quoteUpdateRequestTransfer->getQuoteUpdateRequestAttributes()
            ->setShareDetails($quoteTransfer->getShareDetails());

        return $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateQuoteShareDetailsAccordinglyToShareCartRequest(
        ShareCartRequestTransfer $shareCartRequestTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $cartShareDetails = $shareCartRequestTransfer->getShareDetails();

        $filteredShareDetails = $this->filterShareDetailsWithoutQuotePermissionGroup($cartShareDetails);
        $quoteTransfer->setShareDetails($filteredShareDetails);

        return $quoteTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShareDetailTransfer[] $shareDetails
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShareDetailTransfer[]
     */
    protected function filterShareDetailsWithoutQuotePermissionGroup(ArrayObject $shareDetails): ArrayObject
    {
        $filteredShareDetails = new ArrayObject();
        foreach ($shareDetails as $shareDetail) {
            if ($shareDetail->getQuotePermissionGroup() === null) {
                continue;
            }

            $filteredShareDetails[] = $shareDetail;
        }

        return $filteredShareDetails;
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function validateShareCartRequest(ShareCartRequestTransfer $shareCartRequestTransfer, QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getShareDetails() as $shareDetailTransfer) {
            if ($shareDetailTransfer->getIdCompanyUser() === $shareCartRequestTransfer->getIdCompanyUser()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int $idQuote
     *
     * @throws \Spryker\Client\SharedCart\Exception\CartNotFoundException
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuote(int $idQuote): QuoteTransfer
    {
        $customerQuoteCollectionTransfer = $this->multiCartClient->getQuoteCollection();
        foreach ($customerQuoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getIdQuote() === $idQuote) {
                $quoteTransfer->setCustomer($this->customerClient->getCustomer());

                return $quoteTransfer;
            }
        }

        throw new CartNotFoundException(
            sprintf('Cart with id %s was not found', $idQuote)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailTransfer
     */
    protected function createShareCartDetail(ShareCartRequestTransfer $shareCartRequestTransfer): ShareDetailTransfer
    {
        $shareDetailTransfer = new ShareDetailTransfer();
        $shareDetailTransfer->setIdCompanyUser($shareCartRequestTransfer->getIdCompanyUser());
        $shareDetailTransfer->setQuotePermissionGroup($this->findQuotePermissionGroup($shareCartRequestTransfer->getIdQuotePermissionGroup()));

        return $shareDetailTransfer;
    }

    /**
     * @param int $idQuotePermissionGroup
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer|null
     */
    protected function findQuotePermissionGroup(int $idQuotePermissionGroup): ?QuotePermissionGroupTransfer
    {
        $criteriaFilterTransfer = new QuotePermissionGroupCriteriaFilterTransfer();
        $quotePermissionGroupTransferList = $this->sharedCartStub->getQuotePermissionGroupList($criteriaFilterTransfer);
        foreach ($quotePermissionGroupTransferList->getQuotePermissionGroups() as $quotePermissionGroupTransfer) {
            if ($quotePermissionGroupTransfer->getIdQuotePermissionGroup() === $idQuotePermissionGroup) {
                return $quotePermissionGroupTransfer;
            }
        }

        return null;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShareDetailTransfer[] $shareDetailTransferList
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShareDetailTransfer[]
     */
    protected function filterShareCartToRemove(ArrayObject $shareDetailTransferList, ShareCartRequestTransfer $shareCartRequestTransfer)
    {
        $filteredShareDetailTransferList = new ArrayObject();
        foreach ($shareDetailTransferList as $shareDetailTransfer) {
            if ($shareDetailTransfer->getIdCompanyUser() === $shareCartRequestTransfer->getIdCompanyUser()) {
                continue;
            }
            $filteredShareDetailTransferList->append($shareDetailTransfer);
        }

        return $filteredShareDetailTransferList;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteUpdateRequestTransfer
     */
    protected function createQuoteUpdateRequest(QuoteTransfer $quoteTransfer): QuoteUpdateRequestTransfer
    {
        $quoteUpdateRequestTransfer = new QuoteUpdateRequestTransfer();
        $quoteUpdateRequestTransfer->setIdQuote($quoteTransfer->getIdQuote());
        $quoteUpdateRequestTransfer->setCustomer($quoteTransfer->getCustomer());
        $quoteUpdateRequestAttributesTransfer = new QuoteUpdateRequestAttributesTransfer();
        $quoteUpdateRequestTransfer->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);

        return $quoteUpdateRequestTransfer;
    }
}
