<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Marker;

use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributeMapArchiveTableMap;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributeMapTableMap;

class ProductSearchAttributeMapMarker extends AbstractAttributeMarker
{
    /**
     * @return array
     */
    protected function getAttributeNames()
    {
        return array_merge(
            $this->queryAttributeKeys($this->productSearchQueryContainer->queryProductSearchAttributeMapBySynced(static::NOT_SYNCED)),
            $this->queryArchivedAttributeKeys(
                $this->productSearchQueryContainer->queryProductSearchAttributeMapArchive(),
                SpyProductSearchAttributeMapArchiveTableMap::COL_FK_PRODUCT_ATTRIBUTE_KEY
            )
        );
    }

    /**
     * @param array $attributeNames
     *
     * @return void
     */
    protected function processAttributes(array $attributeNames)
    {
        $this->touchProductAbstractByAttributeNames($attributeNames);
        $this->markProductSearchAttributeMapAsSynced();
        $this->clearProductSearchAttributeMapArchive();
    }

    /**
     * @return void
     */
    protected function markProductSearchAttributeMapAsSynced()
    {
        $syncedFieldName = SpyProductSearchAttributeMapTableMap::getTableMap()
            ->getColumn(SpyProductSearchAttributeMapTableMap::COL_SYNCED)
            ->getPhpName();

        $this->productSearchQueryContainer
            ->queryProductSearchAttributeMapBySynced(static::NOT_SYNCED)
            ->update([
                $syncedFieldName => true,
            ]);
    }

    /**
     * @return void
     */
    protected function clearProductSearchAttributeMapArchive()
    {
        $this->productSearchQueryContainer
            ->queryProductSearchAttributeMapArchive()
            ->deleteAll();
    }
}
