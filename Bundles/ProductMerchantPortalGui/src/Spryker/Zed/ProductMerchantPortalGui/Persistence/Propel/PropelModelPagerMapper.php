<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel;

use Generated\Shared\Transfer\PaginationTransfer;
use Propel\Runtime\Util\PropelModelPager;

class PropelModelPagerMapper
{
    /**
     * @param \Propel\Runtime\Util\PropelModelPager<mixed> $propelPager
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    public function mapPropelModelPagerToPaginationTransfer(
        PropelModelPager $propelPager,
        PaginationTransfer $paginationTransfer
    ): PaginationTransfer {
        return $paginationTransfer
            ->setNbResults($propelPager->getNbResults())
            ->setPage($propelPager->getPage())
            ->setMaxPerPage($propelPager->getMaxPerPage())
            ->setFirstIndex($propelPager->getFirstIndex())
            ->setFirstIndex($propelPager->getFirstIndex())
            ->setLastIndex($propelPager->getLastIndex())
            ->setFirstPage($propelPager->getFirstPage())
            ->setLastPage($propelPager->getLastPage())
            ->setNextPage($propelPager->getNextPage())
            ->setPreviousPage($propelPager->getPreviousPage());
    }
}
