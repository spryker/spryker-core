<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Shared\SharedCart\SharedCartConfig;
use Spryker\Zed\SharedCart\Business\Model\QuoteCompanyUserWriterInterface;
use Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

class ResourceShareQuoteCompanyUserWriter implements ResourceShareQuoteCompanyUserWriterInterface
{
    protected const GLOSSARY_KEY_CART_WAS_SUCCESSFULLY_SHARED = 'shared_cart_page.share.success';
    protected const GLOSSARY_KEY_UNABLE_TO_SHARE_CART = 'shared_cart.resource_share.strategy.error.unable_to_share_cart';
    protected const GLOSSARY_KEY_CART_SHARE_ACCESS_UPDATED = 'shared_cart.resource_share.strategy.success.cart_share_access_updated';

    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected $sharedCartRepository;

    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface
     */
    protected $sharedCartEntityManager;

    /**
     * @var \Spryker\Zed\SharedCart\Business\Model\QuoteCompanyUserWriterInterface
     */
    protected $quoteCompanyUserWriter;

    /**
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface $sharedCartEntityManager
     * @param \Spryker\Zed\SharedCart\Business\Model\QuoteCompanyUserWriterInterface $quoteCompanyUserWriter
     */
    public function __construct(
        SharedCartRepositoryInterface $sharedCartRepository,
        SharedCartEntityManagerInterface $sharedCartEntityManager,
        QuoteCompanyUserWriterInterface $quoteCompanyUserWriter
    ) {
        $this->sharedCartRepository = $sharedCartRepository;
        $this->sharedCartEntityManager = $sharedCartEntityManager;
        $this->quoteCompanyUserWriter = $quoteCompanyUserWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function createCartShareForCompanyUser(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        $shareOption = $resourceShareRequestTransfer->getResourceShare()
            ->getResourceShareData()
            ->getShareOption();

        $quotePermissionGroupTransfer = $this->findQuotePermissionGroupByName($shareOption);
        if (!$quotePermissionGroupTransfer) {
            return (new ResourceShareResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_UNABLE_TO_SHARE_CART)
                );
        }

        $shareCartRequestTransfer = $this->buildShareCartRequestTransfer(
            $resourceShareRequestTransfer,
            $quotePermissionGroupTransfer
        );

        $this->quoteCompanyUserWriter->addQuoteCompanyUser($shareCartRequestTransfer);

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareRequestTransfer->getResourceShare())
            ->addMessage(
                (new MessageTransfer())->setValue(static::GLOSSARY_KEY_CART_WAS_SUCCESSFULLY_SHARED)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     * @param \Generated\Shared\Transfer\ShareDetailTransfer $shareDetailTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function updateCartShareForCompanyUser(
        ResourceShareRequestTransfer $resourceShareRequestTransfer,
        ShareDetailTransfer $shareDetailTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareDataTransfer = $resourceShareRequestTransfer->getResourceShare()
            ->getResourceShareData();

        $resourceShareOptionName = $resourceShareDataTransfer->getShareOption();
        if ($resourceShareOptionName !== SharedCartConfig::PERMISSION_GROUP_FULL_ACCESS) {
            return (new ResourceShareResponseTransfer())
                ->setIsSuccessful(true)
                ->setResourceShare($resourceShareRequestTransfer->getResourceShare());
        }

        $fullAccessQuotePermissionGroupTransfer = $this->findQuotePermissionGroupByName($resourceShareOptionName);
        if ($shareDetailTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup() !== $fullAccessQuotePermissionGroupTransfer->getIdQuotePermissionGroup()) {
            $shareDetailTransfer->setQuotePermissionGroup($fullAccessQuotePermissionGroupTransfer);
            $this->sharedCartEntityManager->updateCompanyUserQuotePermissionGroup($shareDetailTransfer);

            return (new ResourceShareResponseTransfer())
                ->setIsSuccessful(true)
                ->setResourceShare($resourceShareRequestTransfer->getResourceShare())
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_CART_SHARE_ACCESS_UPDATED)
                );
        }

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareRequestTransfer->getResourceShare());
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer $quotePermissionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer
     */
    protected function buildShareCartRequestTransfer(
        ResourceShareRequestTransfer $resourceShareRequestTransfer,
        QuotePermissionGroupTransfer $quotePermissionGroupTransfer
    ): ShareCartRequestTransfer {
        $resourceShareDataTransfer = $resourceShareRequestTransfer->getResourceShare()
            ->getResourceShareData();

        $idCompanyUser = $resourceShareRequestTransfer->getCustomer()
            ->getCompanyUserTransfer()
            ->getIdCompanyUser();

        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setIdCompanyUser($idCompanyUser)
            ->setQuotePermissionGroup($quotePermissionGroupTransfer);

        return (new ShareCartRequestTransfer())
            ->setIdQuote($resourceShareDataTransfer->getIdQuote())
            ->setIdCompanyUser($idCompanyUser)
            ->addShareDetail($shareDetailTransfer);
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer|null
     */
    protected function findQuotePermissionGroupByName(string $name): ?QuotePermissionGroupTransfer
    {
        $quotePermissionGroups = $this->sharedCartRepository->findQuotePermissionGroupList(
            (new QuotePermissionGroupCriteriaFilterTransfer())->setName($name)
        );

        if (!count($quotePermissionGroups)) {
            return null;
        }

        return reset($quotePermissionGroups);
    }
}
