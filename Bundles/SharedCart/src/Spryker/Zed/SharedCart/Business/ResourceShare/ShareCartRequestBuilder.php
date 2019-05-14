<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\ResourceShare;

use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

class ShareCartRequestBuilder implements ShareCartRequestBuilderInterface
{
    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected $sharedCartRepository;

    /**
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     */
    public function __construct(SharedCartRepositoryInterface $sharedCartRepository)
    {
        $this->sharedCartRepository = $sharedCartRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer|null
     */
    public function buildShareCartRequestTransfer(ResourceShareRequestTransfer $resourceShareRequestTransfer): ?ShareCartRequestTransfer
    {
        $resourceShareDataTransfer = $resourceShareRequestTransfer->getResourceShare()
            ->getResourceShareData();

        $idCompanyUser = $resourceShareRequestTransfer->getCustomer()
            ->getCompanyUserTransfer()
            ->getIdCompanyUser();

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
        $quotePermissionGroups = $this->sharedCartRepository->findQuotePermissionGroupList(
            (new QuotePermissionGroupCriteriaFilterTransfer())->setName($shareOption)
        );

        if (!count($quotePermissionGroups)) {
            return null;
        }

        return (new ShareDetailTransfer())
            ->setIdCompanyUser($idCompanyUser)
            ->setQuotePermissionGroup(reset($quotePermissionGroups));
    }
}
