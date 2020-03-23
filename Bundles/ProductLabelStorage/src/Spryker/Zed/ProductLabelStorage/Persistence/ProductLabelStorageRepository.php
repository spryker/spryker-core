<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStoragePersistenceFactory getFactory()
 */
class ProductLabelStorageRepository extends AbstractRepository implements ProductLabelStorageRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[]
     */
    public function getProductLabelLocalizedAttributes(): array
    {
        $productLabelLocalizedAttributesEntities = $this->getFactory()
            ->getProductLabelQuery()
            ->queryAllLocalizedAttributesLabels()
            ->joinWithSpyLocale()
            ->joinWithSpyProductLabel()
            ->addAnd(SpyProductLabelTableMap::COL_IS_ACTIVE, true, Criteria::EQUAL)
            ->find();

        $productLabelLocalizedAttributesTransfers = [];

        foreach ($productLabelLocalizedAttributesEntities as $productLabelLocalizedAttributesEntity) {
            $productLabelLocalizedAttributesTransfers[] = $this->getFactory()
                ->createProductLabelLocalizedAttributesMapper()
                ->mapProductLabelLocalizedAttributesEntityToProductLabelLocalizedAttributesTransfer(
                    $productLabelLocalizedAttributesEntity,
                    new ProductLabelLocalizedAttributesTransfer()
                );
        }

        return $productLabelLocalizedAttributesTransfers;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[]
     */
    public function getProductLabelDictionaryStorageTransfers(): array
    {
        $productLabelDictionaryStorageEntities = $this->getFactory()
            ->createSpyProductLabelDictionaryStorageQuery()
            ->find();

        $productLabelDictionaryStorageTransfers = [];

        foreach ($productLabelDictionaryStorageEntities as $productLabelDictionaryStorageEntity) {
            $productLabelDictionaryStorageTransfers[] = $this->getFactory()
                ->createProductLabelDictionaryStorageMapper()
                ->mapProductLabelDictionaryStorageEntityToProductLabelDictionaryStorageTransfer(
                    $productLabelDictionaryStorageEntity,
                    new ProductLabelDictionaryStorageTransfer()
                );
        }

        return $productLabelDictionaryStorageTransfers;
    }
}
