<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Reader;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generator;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToMerchantFacadeInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToMerchantFacadeInterface
     */
    protected AclMerchantPortalToMerchantFacadeInterface $merchantFacade;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig
     */
    protected AclMerchantPortalConfig $merchantPortalConfig;

    /**
     * @param \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig $merchantPortalConfig
     * @param \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(
        AclMerchantPortalConfig $merchantPortalConfig,
        AclMerchantPortalToMerchantFacadeInterface $merchantFacade
    ) {
        $this->merchantFacade = $merchantFacade;
        $this->merchantPortalConfig = $merchantPortalConfig;
    }

    /**
     * @return \Generator<array<\Generated\Shared\Transfer\MerchantTransfer>>
     */
    public function getMerchantTransfersGenerator(): Generator
    {
        $offset = 0;
        $totalCount = 0;
        $limit = $this->merchantPortalConfig->getAclEntitySynchronizationMerchantReadBatchSize();

        do {
            $merchantCriteriaTransfer = $this->createMerchantCriteriaTransfer($offset, $limit);
            $merchantCollectionTransfer = $this->merchantFacade->get($merchantCriteriaTransfer);

            yield $merchantCollectionTransfer->getMerchants()->getArrayCopy();

            $totalCount += $merchantCollectionTransfer->getMerchants()->count();
            $offset += $limit;
        } while ($totalCount === $offset);
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\MerchantCriteriaTransfer
     */
    protected function createMerchantCriteriaTransfer(int $offset, int $limit): MerchantCriteriaTransfer
    {
        $paginationTransfer = (new PaginationTransfer())
            ->setOffset($offset)
            ->setLimit($limit);

        return (new MerchantCriteriaTransfer())->setPagination($paginationTransfer);
    }
}
