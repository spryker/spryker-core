<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Marker;

use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributeArchiveTableMap;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributeTableMap;

class ProductSearchAttributeMarker extends AbstractAttributeMarker
{
    /**
     * @return array
     */
    protected function getAttributeNames()
    {
        return array_merge(
            $this->queryAttributeKeys($this->productSearchQueryContainer->queryProductSearchAttributeBySynced(static::NOT_SYNCED)),
            $this->queryArchivedAttributeKeys(
                $this->productSearchQueryContainer->queryProductSearchAttributeArchive(),
                SpyProductSearchAttributeArchiveTableMap::COL_FK_PRODUCT_ATTRIBUTE_KEY
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
        $this->markProductSearchAttributesAsSynced();
        $this->clearProductSearchAttributeArchive();
    }

    /**
     * @return void
     */
    protected function markProductSearchAttributesAsSynced()
    {
        $syncedFieldName = SpyProductSearchAttributeTableMap::getTableMap()
            ->getColumn(SpyProductSearchAttributeTableMap::COL_SYNCED)
            ->getPhpName();

        $this->productSearchQueryContainer
            ->queryProductSearchAttributeBySynced(static::NOT_SYNCED)
            ->update([
                $syncedFieldName => true,
            ]);
    }

    /**
     * @return void
     */
    protected function clearProductSearchAttributeArchive()
    {
        $this->productSearchQueryContainer
            ->queryProductSearchAttributeArchive()
            ->deleteAll();
    }
}
