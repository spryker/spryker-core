<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Reader;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generator;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToMerchantUserFacadeInterface;

class MerchantUserReader implements MerchantUserReaderInterface
{
    /**
     * @var \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig
     */
    protected AclMerchantPortalConfig $merchantPortalConfig;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToMerchantUserFacadeInterface
     */
    protected AclMerchantPortalToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @param \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig $merchantPortalConfig
     * @param \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        AclMerchantPortalConfig $merchantPortalConfig,
        AclMerchantPortalToMerchantUserFacadeInterface $merchantUserFacade
    ) {
        $this->merchantPortalConfig = $merchantPortalConfig;
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @return \Generator<array<\Generated\Shared\Transfer\MerchantUserTransfer>>
     */
    public function getMerchantUserTransfersGenerator(): Generator
    {
        $offset = 0;
        $totalCount = 0;
        $limit = $this->merchantPortalConfig->getAclEntitySynchronizationMerchantUserReadBatchSize();

        do {
            $merchantUserCriteriaTransfer = $this->createMerchantUserCriteriaTransfer($offset, $limit);
            $merchantUserCollectionTransfer = $this->merchantUserFacade->getMerchantUserCollection($merchantUserCriteriaTransfer);

            yield $merchantUserCollectionTransfer->getMerchantUsers()->getArrayCopy();

            $totalCount += $merchantUserCollectionTransfer->getMerchantUsers()->count();
            $offset += $limit;
        } while ($totalCount === $offset);
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\MerchantUserCriteriaTransfer
     */
    protected function createMerchantUserCriteriaTransfer(int $offset, int $limit): MerchantUserCriteriaTransfer
    {
        $paginationTransfer = (new PaginationTransfer())
            ->setOffset($offset)
            ->setLimit($limit);

        return (new MerchantUserCriteriaTransfer())->setPagination($paginationTransfer);
    }
}
