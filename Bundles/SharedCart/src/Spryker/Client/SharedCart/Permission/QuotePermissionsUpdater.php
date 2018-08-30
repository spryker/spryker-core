<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Permission;

use ArrayObject;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Client\SharedCart\Dependency\Client\SharedCartToMultiCartClientInterface;
use Spryker\Client\SharedCart\Dependency\Client\SharedCartToPersistentCartClientInterface;
use Spryker\Client\SharedCart\Exception\CartNotFoundException;

class QuotePermissionsUpdater implements QuotePermissionsUpdaterInterface
{
    /**
     * @var \Spryker\Client\SharedCart\Dependency\Client\SharedCartToMultiCartClientInterface
     */
    protected $multiCartClient;

    /**
     * @var \Spryker\Client\SharedCart\Dependency\Client\SharedCartToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @param \Spryker\Client\SharedCart\Dependency\Client\SharedCartToMultiCartClientInterface $multiCartClient
     * @param \Spryker\Client\SharedCart\Dependency\Client\SharedCartToPersistentCartClientInterface $persistentCartClient
     */
    public function __construct(
        SharedCartToMultiCartClientInterface $multiCartClient,
        SharedCartToPersistentCartClientInterface $persistentCartClient
    ) {
        $this->multiCartClient = $multiCartClient;
        $this->persistentCartClient = $persistentCartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuotePermissions(ShareCartRequestTransfer $shareCartRequestTransfer): QuoteResponseTransfer
    {
        $quoteTransfer = $this->getQuote($shareCartRequestTransfer->getIdQuote());
        $quoteTransfer = $this->addCartShareDetailsToQuote($shareCartRequestTransfer, $quoteTransfer);
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
    protected function addCartShareDetailsToQuote(
        ShareCartRequestTransfer $shareCartRequestTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $cartShareDetails = $shareCartRequestTransfer->getShareDetails();
        $quoteShareDetails = (array)$quoteTransfer->getShareDetails();

        $filteredShareDetails = new ArrayObject();
        foreach ($cartShareDetails as $cartShareDetail) {
            if (!$this->validateCartShareDetail($cartShareDetail)) {
                continue;
            }

            $quoteShareDetail = $this->findQuoteShareDetailByIdCompanyUser(
                $cartShareDetail->getIdCompanyUser(),
                $quoteShareDetails
            );

            if ($quoteShareDetail !== null
                && !$this->isQuotePermissionGroupsEqual($quoteShareDetail, $cartShareDetail)
            ) {
                $quoteShareDetail->setQuotePermissionGroup(
                    $cartShareDetail->getQuotePermissionGroup()
                );
                continue;
            }

            $filteredShareDetails->append($cartShareDetail);
        }
        $quoteTransfer->setShareDetails($filteredShareDetails);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShareDetailTransfer $quoteShareDetailTransfer
     * @param \Generated\Shared\Transfer\ShareDetailTransfer $cartShareDetailsTransfer
     *
     * @return bool
     */
    protected function isQuotePermissionGroupsEqual(
        ShareDetailTransfer $quoteShareDetailTransfer,
        ShareDetailTransfer $cartShareDetailsTransfer
    ): bool {
        $quoteShareDetailPermissionGroup = $quoteShareDetailTransfer->getQuotePermissionGroup();
        $cartShareDetailPermissionGroup = $cartShareDetailsTransfer->getQuotePermissionGroup();

        if (!$quoteShareDetailPermissionGroup || !$cartShareDetailPermissionGroup) {
            return false;
        }

        return $quoteShareDetailPermissionGroup === $cartShareDetailPermissionGroup;
    }

    /**
     * @param int $idCompanyUser
     * @param \Generated\Shared\Transfer\ShareDetailTransfer[] $quoteShareDetails
     *
     * @return \Generated\Shared\Transfer\ShareDetailTransfer|null
     */
    protected function findQuoteShareDetailByIdCompanyUser(
        int $idCompanyUser,
        array $quoteShareDetails
    ): ?ShareDetailTransfer {
        foreach ($quoteShareDetails as $quoteShareDetail) {
            if ($quoteShareDetail->getIdCompanyUser() === $idCompanyUser) {
                return $quoteShareDetail;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShareDetailTransfer $cartShareDetail
     *
     * @return bool
     */
    protected function validateCartShareDetail(ShareDetailTransfer $cartShareDetail): bool
    {
        return $cartShareDetail->getIdCompanyUser() && $cartShareDetail->getQuotePermissionGroup();
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
                return $quoteTransfer;
            }
        }

        throw new CartNotFoundException(
            sprintf('Cart with id %s was not found', $idQuote)
        );
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
