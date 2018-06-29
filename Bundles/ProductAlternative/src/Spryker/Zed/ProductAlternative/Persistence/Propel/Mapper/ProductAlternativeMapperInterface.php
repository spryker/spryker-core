<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeListItemTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative;
use Propel\Runtime\Collection\Collection;

interface ProductAlternativeMapperInterface
{
    /**
     * @param \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative $productAlternativeEntity
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function mapProductAlternativeTransfer(SpyProductAlternative $productAlternativeEntity): ProductAlternativeTransfer;

    /**
     * @param \Propel\Runtime\Collection\Collection|null $productAlternativeEntities
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function mapProductAlternativeCollectionTransfer(?Collection $productAlternativeEntities): ProductAlternativeCollectionTransfer;

    /**
     * @param array $productConcreteData
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function mapProductConcreteDataToProductAlternativeListItemTransfer(array $productConcreteData): ProductAlternativeListItemTransfer;

    /**
     * @param array $productAbstractData
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function mapProductAbstractDataToProductAlternativeListItemTransfer(array $productAbstractData): ProductAlternativeListItemTransfer;
}
