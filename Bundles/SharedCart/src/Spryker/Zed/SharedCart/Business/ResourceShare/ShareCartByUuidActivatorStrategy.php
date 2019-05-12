<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\ResourceShare;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Zed\SharedCart\Business\Model\QuoteCompanyUserWriterInterface;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

class ShareCartByUuidActivatorStrategy implements ShareCartByUuidActivatorStrategyInterface
{
    protected const GLOSSARY_KEY_CART_ACCESS_DENIED = 'shared_cart.resource_share.strategy.cart_access_denied';
    protected const GLOSSARY_KEY_ONE_OR_MORE_REQUIRED_PROPERTIES_ARE_MISSING = 'shared_cart.resource_share.strategy.error.properties_are_missing';
    protected const GLOSSARY_KEY_UNABLE_TO_SHARE_CART = 'shared_cart.resource_share.strategy.error.unable_to_share_cart';

    /**
     * @var \Spryker\Zed\SharedCart\Business\Model\QuoteCompanyUserWriterInterface
     */
    protected $quoteCompanyUserWriter;

    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected $sharedCartRepository;

    /**
     * @param \Spryker\Zed\SharedCart\Business\Model\QuoteCompanyUserWriterInterface $quoteCompanyUserWriter
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     */
    public function __construct(
        QuoteCompanyUserWriterInterface $quoteCompanyUserWriter,
        SharedCartRepositoryInterface $sharedCartRepository
    ) {
        $this->quoteCompanyUserWriter = $quoteCompanyUserWriter;
        $this->sharedCartRepository = $sharedCartRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function applyResourceShareActivatorStrategy(
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareRequestTransfer->requireCustomer()
            ->requireResourceShare();

        $resourceShareTransfer = $resourceShareRequestTransfer->getResourceShare();
        $resourceShareResponseTransfer = $this->validateResourceShareTransfer($resourceShareTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        $customerTransfer = $resourceShareRequestTransfer->getCustomer();
        $idCompanyBusinessUnit = $this->findIdCompanyBusinessUnit($customerTransfer);
        if (!$idCompanyBusinessUnit) {
            return $resourceShareResponseTransfer->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_CART_ACCESS_DENIED)
                );
        }

        $resourceShareTransfer->requireResourceShareData();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();
        if ($resourceShareDataTransfer->getOwnerIdCompanyBusinessUnit() !== $idCompanyBusinessUnit) {
            return $resourceShareResponseTransfer->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_CART_ACCESS_DENIED)
                );
        }

        return $this->shareCartWithProvidedResourceShareCompanyUser($resourceShareRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function shareCartWithProvidedResourceShareCompanyUser(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        $shareCartRequestTransfer = $this->createShareCartRequestTransfer($resourceShareRequestTransfer);
        if (!$shareCartRequestTransfer) {
            return (new ResourceShareResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_UNABLE_TO_SHARE_CART)
                );
        }

        $storedShareDetailTransfer = $this->findStoredShareDetailByIdQuoteAndIdCompanyUser($shareCartRequestTransfer);
        if (!$storedShareDetailTransfer) {
            $this->quoteCompanyUserWriter->addQuoteCompanyUser($shareCartRequestTransfer);
        }

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareRequestTransfer->getResourceShare());
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer|null
     */
    protected function createShareCartRequestTransfer(ResourceShareRequestTransfer $resourceShareRequestTransfer): ?ShareCartRequestTransfer
    {
        $resourceShareDataTransfer = $resourceShareRequestTransfer->getResourceShare()
            ->getResourceShareData();

        $idCompanyUser = $resourceShareDataTransfer->getOwnerIdCompanyUser();
        $shareDetailTransfer = $this->createShareDetailTransfer($idCompanyUser, $resourceShareDataTransfer->getShareOption());
        if (!$shareDetailTransfer) {
            return null;
        }

        return (new ShareCartRequestTransfer())
            ->setIdQuote($resourceShareDataTransfer->getIdQuote())
            ->setIdCompanyUser($idCompanyUser)
            ->addShareDetail($shareDetailTransfer);
    }

    /**
     * @param int $idCompanyUser
     * @param string $shareOption
     *
     * @return \Generated\Shared\Transfer\ShareDetailTransfer|null
     */
    protected function createShareDetailTransfer(int $idCompanyUser, string $shareOption): ?ShareDetailTransfer
    {
        $quotePermissionGroupTransfer = $this->findResolvedQuotePermissionGroup($shareOption);
        if (!$quotePermissionGroupTransfer) {
            return null;
        }

        return (new ShareDetailTransfer())
            ->setIdCompanyUser($idCompanyUser)
            ->setQuotePermissionGroup($quotePermissionGroupTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int|null
     */
    protected function findIdCompanyBusinessUnit(CustomerTransfer $customerTransfer): ?int
    {
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if (!$companyUserTransfer) {
            return null;
        }

        $companyBusinessUnitTransfer = $companyUserTransfer->getCompanyBusinessUnit();

        return $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailTransfer|null
     */
    protected function findStoredShareDetailByIdQuoteAndIdCompanyUser(ShareCartRequestTransfer $shareCartRequestTransfer): ?ShareDetailTransfer
    {
        $shareDetailCollectionTransfer = $this->sharedCartRepository->findShareDetailsByQuoteId($shareCartRequestTransfer->getIdQuote());

        $requestShareDetailTransfer = $shareCartRequestTransfer->getShareDetails()->offsetGet(0);
        $requestIdQuotePermissionGroup = $requestShareDetailTransfer->getQuotePermissionGroup()
            ->getIdQuotePermissionGroup();

        foreach ($shareDetailCollectionTransfer->getShareDetails() as $storedShareDetailTransfer) {
            $storedIdQuotePermissionGroup = $storedShareDetailTransfer->getQuotePermissionGroup()
                ->getIdQuotePermissionGroup();

            if ($storedIdQuotePermissionGroup === $requestIdQuotePermissionGroup
                && $storedShareDetailTransfer->getIdCompanyUser() === $requestShareDetailTransfer->getIdCompanyUser()
            ) {
                return $storedShareDetailTransfer;
            }
        }

        return null;
    }

    /**
     * @param string $shareOption
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer|null
     */
    protected function findResolvedQuotePermissionGroup(
        string $shareOption
    ): ?QuotePermissionGroupTransfer {
        $indexedQuotePermissionGroups = $this->getIndexedQuotePermissionGroups();

        return $indexedQuotePermissionGroups[$shareOption] ?? null;
    }

    /**
     * @return array [name => QuotePermissionGroupTransfer]
     */
    protected function getIndexedQuotePermissionGroups(): array
    {
        $quotePermissionGroups = $this->sharedCartRepository->findQuotePermissionGroupList(
            new QuotePermissionGroupCriteriaFilterTransfer()
        );

        $indexedQuotePermissionGroups = [];
        foreach ($quotePermissionGroups as $quotePermissionGroupTransfer) {
            $indexedQuotePermissionGroups[$quotePermissionGroupTransfer->getName()] = $quotePermissionGroupTransfer;
        }

        return $indexedQuotePermissionGroups;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function validateResourceShareTransfer(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = new ResourceShareResponseTransfer();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        if ($resourceShareDataTransfer->getShareOption()
            && $resourceShareDataTransfer->getIdQuote()
            && $resourceShareDataTransfer->getOwnerIdCompanyUser()
            && $resourceShareDataTransfer->getOwnerIdCompanyBusinessUnit()
        ) {
            return $resourceShareResponseTransfer->setIsSuccessful(true)
                ->setResourceShare($resourceShareTransfer);
        }

        return $resourceShareResponseTransfer->setIsSuccessful(false)
            ->addMessage(
                (new MessageTransfer())->setValue(static::GLOSSARY_KEY_ONE_OR_MORE_REQUIRED_PROPERTIES_ARE_MISSING)
            );
    }
}
