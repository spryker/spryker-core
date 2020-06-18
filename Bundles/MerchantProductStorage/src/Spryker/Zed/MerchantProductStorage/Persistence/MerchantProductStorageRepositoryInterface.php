<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Persistence;

use Generated\Shared\Transfer\MerchantProductCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductFilterCriteriaTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface MerchantProductStorageRepositoryInterface
{
    /**
     * @param int[] $idProductAbstracts
     *
     * @return \Generated\Shared\Transfer\MerchantProductCollectionTransfer
     */
    public function getMerchantProductsByIdProductAbstracts(array $idProductAbstracts): MerchantProductCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantProductFilterCriteriaTransfer $merchantProductFilterCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProductStorage\Persistence\SpyMerchantProductAbstractStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getMerchantProductStorageEntitiesByFilterCriteria(
        MerchantProductFilterCriteriaTransfer $merchantProductFilterCriteriaTransfer
    ): ObjectCollection;
}
