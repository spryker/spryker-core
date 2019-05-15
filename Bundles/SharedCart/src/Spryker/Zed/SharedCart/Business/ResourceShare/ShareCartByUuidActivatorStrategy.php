<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

class ShareCartByUuidActivatorStrategy implements ShareCartByUuidActivatorStrategyInterface
{
    protected const GLOSSARY_KEY_CART_ACCESS_DENIED = 'shared_cart.resource_share.strategy.cart_access_denied';

    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected $sharedCartRepository;

    /**
     * @var \Spryker\Zed\SharedCart\Business\ResourceShare\ResourceShareQuoteCompanyUserWriterInterface
     */
    protected $resourceShareQuoteCompanyUserWriter;

    /**
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     * @param \Spryker\Zed\SharedCart\Business\ResourceShare\ResourceShareQuoteCompanyUserWriterInterface $resourceShareQuoteCompanyUserWriter
     */
    public function __construct(
        SharedCartRepositoryInterface $sharedCartRepository,
        ResourceShareQuoteCompanyUserWriterInterface $resourceShareQuoteCompanyUserWriter
    ) {
        $this->sharedCartRepository = $sharedCartRepository;
        $this->resourceShareQuoteCompanyUserWriter = $resourceShareQuoteCompanyUserWriter;
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

        if (!$this->isProvidedCompanyUserAllowedToShareCart($resourceShareRequestTransfer)) {
            return (new ResourceShareResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_CART_ACCESS_DENIED)
                );
        }

        $shareDetailTransfer = $this->findShareDetail($resourceShareRequestTransfer);
        if ($shareDetailTransfer) {
            return $this->resourceShareQuoteCompanyUserWriter->updateCartShareForProvidedCompanyUser($resourceShareRequestTransfer, $shareDetailTransfer);
        }

        return $this->resourceShareQuoteCompanyUserWriter->createCartShareForProvidedCompanyUser($resourceShareRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailTransfer|null
     */
    protected function findShareDetail(ResourceShareRequestTransfer $resourceShareRequestTransfer): ?ShareDetailTransfer
    {
        $idCompanyUser = $resourceShareRequestTransfer->getCustomer()
            ->getCompanyUserTransfer()
            ->getIdCompanyUser();

        $idQuote = $resourceShareRequestTransfer->getResourceShare()
            ->getResourceShareData()
            ->getIdQuote();

        return $this->sharedCartRepository->findShareDetailByIdQuoteAndIdCompanyUser($idQuote, $idCompanyUser);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return bool
     */
    protected function isProvidedCompanyUserAllowedToShareCart(ResourceShareRequestTransfer $resourceShareRequestTransfer): bool
    {
        $customerTransfer = $resourceShareRequestTransfer->getCustomer();

        $customerTransfer->requireCompanyUserTransfer();
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();

        $resourceShareTransfer = $resourceShareRequestTransfer->getResourceShare();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        $ownerIdCompanyBusinessUnit = $resourceShareDataTransfer->getOwnerIdCompanyBusinessUnit();
        if (!$ownerIdCompanyBusinessUnit || $ownerIdCompanyBusinessUnit !== $companyUserTransfer->getFkCompanyBusinessUnit()) {
            return false;
        }

        return true;
    }
}
