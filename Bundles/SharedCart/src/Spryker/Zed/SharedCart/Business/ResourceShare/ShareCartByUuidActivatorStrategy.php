<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Shared\SharedCart\SharedCartConfig;
use Spryker\Zed\SharedCart\Business\Model\QuoteCompanyUserWriterInterface;
use Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

class ShareCartByUuidActivatorStrategy implements ShareCartByUuidActivatorStrategyInterface
{
    protected const GLOSSARY_KEY_CART_ACCESS_DENIED = 'shared_cart.resource_share.strategy.cart_access_denied';
    protected const GLOSSARY_KEY_UNABLE_TO_SHARE_CART = 'shared_cart.resource_share.strategy.error.unable_to_share_cart';

    /**
     * @var \Spryker\Zed\SharedCart\Business\Model\QuoteCompanyUserWriterInterface
     */
    protected $quoteCompanyUserWriter;

    /**
     * @var \Spryker\Zed\SharedCart\Business\ResourceShare\ShareCartRequestBuilderInterface
     */
    protected $shareCartRequestBuilder;

    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected $sharedCartRepository;

    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface
     */
    protected $sharedCartEntityManager;

    /**
     * @param \Spryker\Zed\SharedCart\Business\Model\QuoteCompanyUserWriterInterface $quoteCompanyUserWriter
     * @param \Spryker\Zed\SharedCart\Business\ResourceShare\ShareCartRequestBuilderInterface $shareCartRequestBuilder
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface $sharedCartEntityManager
     */
    public function __construct(
        QuoteCompanyUserWriterInterface $quoteCompanyUserWriter,
        ShareCartRequestBuilderInterface $shareCartRequestBuilder,
        SharedCartRepositoryInterface $sharedCartRepository,
        SharedCartEntityManagerInterface $sharedCartEntityManager
    ) {
        $this->quoteCompanyUserWriter = $quoteCompanyUserWriter;
        $this->shareCartRequestBuilder = $shareCartRequestBuilder;
        $this->sharedCartRepository = $sharedCartRepository;
        $this->sharedCartEntityManager = $sharedCartEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function applyShareCartByUuidActivatorStrategy(
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareRequestTransfer->requireCustomer()
            ->requireResourceShare();

        if (!$this->isLoggedInCompanyUserAllowedToShareCart($resourceShareRequestTransfer)) {
            return $this->createErrorMessageResponse(static::GLOSSARY_KEY_CART_ACCESS_DENIED);
        }

        return $this->shareCartWithLoggedInCompanyUser($resourceShareRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function shareCartWithLoggedInCompanyUser(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        $shareCartRequestTransfer = $this->shareCartRequestBuilder->buildShareCartRequestTransfer($resourceShareRequestTransfer);
        if (!$shareCartRequestTransfer) {
            return $this->createErrorMessageResponse(static::GLOSSARY_KEY_UNABLE_TO_SHARE_CART);
        }

        $storedShareDetailTransfer = $this->sharedCartRepository->findShareDetailByIdQuoteAndIdCompanyUser($shareCartRequestTransfer);
        if (!$storedShareDetailTransfer) {
            $this->quoteCompanyUserWriter->addQuoteCompanyUser($shareCartRequestTransfer);

            return (new ResourceShareResponseTransfer())
                ->setIsSuccessful(true)
                ->setResourceShare($resourceShareRequestTransfer->getResourceShare());
        }

        $newShareDetailTransfer = $shareCartRequestTransfer->getShareDetails()->offsetGet(0);
        $newQuotePermissionGroupTransfer = $newShareDetailTransfer->getQuotePermissionGroup();
        $storedQuotePermissionGroupTransfer = $storedShareDetailTransfer->getQuotePermissionGroup();

        if ($newQuotePermissionGroupTransfer->getName() === SharedCartConfig::PERMISSION_GROUP_FULL_ACCESS
            && $storedQuotePermissionGroupTransfer->getIdQuotePermissionGroup() !== $newQuotePermissionGroupTransfer->getIdQuotePermissionGroup()
        ) {
            $storedShareDetailTransfer->setQuotePermissionGroup(
                $newShareDetailTransfer->getQuotePermissionGroup()
            );

            $this->sharedCartEntityManager->updateCompanyUserQuotePermissionGroup($storedShareDetailTransfer);
        }

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareRequestTransfer->getResourceShare());
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return bool
     */
    protected function isLoggedInCompanyUserAllowedToShareCart(ResourceShareRequestTransfer $resourceShareRequestTransfer): bool
    {
        $customerTransfer = $resourceShareRequestTransfer->getCustomer();
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if (!$companyUserTransfer) {
            return false;
        }

        $resourceShareTransfer = $resourceShareRequestTransfer->getResourceShare();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        $ownerIdCompanyBusinessUnit = $resourceShareDataTransfer->getOwnerIdCompanyBusinessUnit();
        if (!$ownerIdCompanyBusinessUnit || $ownerIdCompanyBusinessUnit !== $companyUserTransfer->getFkCompanyBusinessUnit()) {
            return false;
        }

        return true;
    }

    /**
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function createErrorMessageResponse(string $errorMessage): ResourceShareResponseTransfer
    {
        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage(
                (new MessageTransfer())->setValue($errorMessage)
            );
    }
}
