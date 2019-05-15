<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
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
    protected const GLOSSARY_KEY_UNABLE_TO_SHARE_CART = 'shared_cart.resource_share.strategy.error.unable_to_share_cart';

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
    public function createCartShareForProvidedCompanyUser(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        $shareCartRequestTransfer = $this->buildShareCartRequestTransfer($resourceShareRequestTransfer);
        if (!$shareCartRequestTransfer) {
            return (new ResourceShareResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_UNABLE_TO_SHARE_CART)
                );
        }

        $this->quoteCompanyUserWriter->addQuoteCompanyUser($shareCartRequestTransfer);

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareRequestTransfer->getResourceShare());
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     * @param \Generated\Shared\Transfer\ShareDetailTransfer $shareDetailTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function updateCartShareForProvidedCompanyUser(
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

        $fullAccessIdQuotePermissionGroup = $this->sharedCartRepository->findIdQuotePermissionGroupByName($resourceShareOptionName);
        if ($shareDetailTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup() !== $fullAccessIdQuotePermissionGroup) {
            $this->updateShareDetailQuotePermissionGroup($shareDetailTransfer, $fullAccessIdQuotePermissionGroup);
        }

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareRequestTransfer->getResourceShare());
    }

    /**
     * @param \Generated\Shared\Transfer\ShareDetailTransfer $shareDetailTransfer
     * @param int $idQuotePermissionGroup
     *
     * @return void
     */
    protected function updateShareDetailQuotePermissionGroup(ShareDetailTransfer $shareDetailTransfer, int $idQuotePermissionGroup): void
    {
        $shareDetailTransfer->getQuotePermissionGroup()->setIdQuotePermissionGroup($idQuotePermissionGroup);

        $this->sharedCartEntityManager->updateCompanyUserQuotePermissionGroup($shareDetailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer|null
     */
    protected function buildShareCartRequestTransfer(ResourceShareRequestTransfer $resourceShareRequestTransfer): ?ShareCartRequestTransfer
    {
        $resourceShareDataTransfer = $resourceShareRequestTransfer->getResourceShare()
            ->getResourceShareData();

        $idCompanyUser = $resourceShareRequestTransfer->getCustomer()
            ->getCompanyUserTransfer()
            ->getIdCompanyUser();

        $quotePermissionGroupTransfer = $this->findQuotePermissionGroupByName(
            $resourceShareDataTransfer->getShareOption()
        );

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
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer
     */
    protected function findQuotePermissionGroupByName(string $name): QuotePermissionGroupTransfer
    {
        $idQuotePermissionGroup = $this->sharedCartRepository->findIdQuotePermissionGroupByName($name);

        return (new QuotePermissionGroupTransfer())
            ->setIdQuotePermissionGroup($idQuotePermissionGroup);
    }
}
