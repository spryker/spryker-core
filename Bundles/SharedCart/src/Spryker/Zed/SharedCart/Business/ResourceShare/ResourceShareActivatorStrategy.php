<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\ResourceShare;

use Generated\Shared\Transfer\CompanyUserTransfer;
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
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCompanyUserFacadeInterface;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

class ResourceShareActivatorStrategy implements ResourceShareActivatorStrategyInterface
{
    protected const GLOSSARY_KEY_CART_ACCESS_DENIED = 'shared_cart.resource_share.strategy.cart_access_denied';
    protected const GLOSSARY_KEY_ONE_OR_MORE_REQUIRED_PROPERTIES_ARE_MISSING = 'shared_cart.resource_share.strategy.error.properties_are_missing';
    protected const GLOSSARY_KEY_UNABLE_TO_SHARE_CART = 'shared_cart.resource_share.strategy.error.unable_to_share_cart';

    protected const QUOTE_RESOURCE_TYPE = 'Quote';
    protected const KEY_SHARE_OPTION = 'share_option';

    /**
     * @var \Spryker\Zed\SharedCart\Business\Model\QuoteCompanyUserWriterInterface
     */
    protected $quoteCompanyUserWriter;

    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected $sharedCartRepository;

    /**
     * @var \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\SharedCart\Business\Model\QuoteCompanyUserWriterInterface $quoteCompanyUserWriter
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     * @param \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(
        QuoteCompanyUserWriterInterface $quoteCompanyUserWriter,
        SharedCartRepositoryInterface $sharedCartRepository,
        SharedCartToCompanyUserFacadeInterface $companyUserFacade
    ) {
        $this->quoteCompanyUserWriter = $quoteCompanyUserWriter;
        $this->sharedCartRepository = $sharedCartRepository;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function applyResourceShareActivatorStrategy(
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareRequestTransfer->requireCustomer();
        $resourceShareRequestTransfer->requireResourceShare();

        $resourceShareTransfer = $resourceShareRequestTransfer->getResourceShare();
        $resourceShareResponseTransfer = $this->validateResourceShareTransfer($resourceShareTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        $customerTransfer = $resourceShareRequestTransfer->getCustomer();
        $companyUserTransfer = $this->findCompanyUserByCustomerReference($customerTransfer);
        if (!$companyUserTransfer) {
            return $resourceShareResponseTransfer->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_CART_ACCESS_DENIED)
                );
        }

        $idCompanyBusinessUnit = $companyUserTransfer->getCompanyBusinessUnit()->getIdCompanyBusinessUnit();
        if ($resourceShareDataTransfer->getIdCompanyBusinessUnit() !== $idCompanyBusinessUnit) {
            return $resourceShareResponseTransfer->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_CART_ACCESS_DENIED)
                );
        }

        return $this->shareCartWithLoggedInCustomer($resourceShareTransfer, $companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function shareCartWithLoggedInCustomer(
        ResourceShareTransfer $resourceShareTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): ResourceShareResponseTransfer {
        $shareCartRequestTransfer = $this->createShareCartRequestTransfer($resourceShareTransfer, $companyUserTransfer);
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
            ->setResourceShare($resourceShareTransfer);
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
            && $resourceShareDataTransfer->getIdCompanyUser()
            && $resourceShareDataTransfer->getIdCompanyBusinessUnit()
        ) {
            return $resourceShareResponseTransfer->setIsSuccessful(true)
                ->setResourceShare($resourceShareTransfer);
        }

        return $resourceShareResponseTransfer->setIsSuccessful(false)
            ->addMessage(
                (new MessageTransfer())->setValue(static::GLOSSARY_KEY_ONE_OR_MORE_REQUIRED_PROPERTIES_ARE_MISSING)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer|null
     */
    protected function createShareCartRequestTransfer(
        ResourceShareTransfer $resourceShareTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): ?ShareCartRequestTransfer {
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        $shareDetailTransfer = $this->createShareDetailTransfer($companyUserTransfer, $resourceShareDataTransfer->getShareOption());
        if (!$shareDetailTransfer) {
            return null;
        }

        $companyUserTransfer = $this->findCompanyUserByCustomerReference(
            (new CustomerTransfer())->setCustomerReference($resourceShareTransfer->getCustomerReference())
        );

        if (!$companyUserTransfer) {
            return null;
        }

        return (new ShareCartRequestTransfer())
            ->setIdQuote($resourceShareDataTransfer->getIdQuote())
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->addShareDetail($shareDetailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param string $shareOption
     *
     * @return \Generated\Shared\Transfer\ShareDetailTransfer|null
     */
    protected function createShareDetailTransfer(CompanyUserTransfer $companyUserTransfer, string $shareOption): ?ShareDetailTransfer
    {
        $idCompanyUser = $companyUserTransfer->getIdCompanyUser();
        if (!$idCompanyUser) {
            return null;
        }

        $quotePermissionGroupTransfer = $this->findResolvedQuotePermissionGroup($shareOption);
        if (!$quotePermissionGroupTransfer) {
            return null;
        }

        return (new ShareDetailTransfer())
            ->setIdCompanyUser($idCompanyUser)
            ->setQuotePermissionGroup($quotePermissionGroupTransfer);
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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function findCompanyUserByCustomerReference(CustomerTransfer $customerTransfer): ?CompanyUserTransfer
    {
        $companyUserCollectionTransfer = $this->companyUserFacade->getActiveCompanyUsersByCustomerReference($customerTransfer);
        $companyUserTransfers = $companyUserCollectionTransfer->getCompanyUsers();

        if ($companyUserTransfers->count() === 0) {
            return null;
        }

        return $companyUserTransfers->offsetGet(0);
    }
}
