<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelCollector\Business\Collector\Storage;

use Spryker\Shared\ProductLabel\ProductLabelConstants;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;
use Spryker\Zed\ProductLabelCollector\Persistence\Collector\Propel\ProductAbstractRelationCollectorQuery;

class ProductAbstractRelationCollector extends AbstractStoragePropelCollector
{
    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $productLabelIds = $this->getProductLabelIds($collectItemData);

        return $productLabelIds;
    }

    /**
     * @param array $collectItemData
     *
     * @return int[]
     */
    protected function getProductLabelIds(array $collectItemData)
    {
        $productLabelIdsCsv = $collectItemData[ProductAbstractRelationCollectorQuery::RESULT_FIELD_ID_PRODUCT_LABELS_CSV];

        $productLabelIds = explode(',', $productLabelIdsCsv);
        $activeIds = $this->filterActiveLabels($productLabelIds);
        if (!$activeIds) {
            return [];
        }

        return array_map('intval', $activeIds);
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS;
    }

    /**
     * @return bool
     */
    protected function isStorageTableJoinWithLocaleEnabled()
    {
        return true;
    }

    /**
     * @param array $productLabelIds
     *
     * @return array
     */
    protected function filterActiveLabels(array $productLabelIds)
    {
        $activeIds = [];
        foreach ($productLabelIds as $labelId) {
            [$idProductLabel, $isActive] = explode(ProductAbstractRelationCollectorQuery::LABEL_DELIMITER, $labelId);

            $isActive = $this->normalizeIsActive($isActive);
            if (!$isActive) {
                continue;
            }
            $activeIds[] = $idProductLabel;
        }

        return $activeIds;
    }

    /**
     * In PostgreSQL the return value for is active is different than MySQL, here we normalize the value.
     *
     * @param string $isActive
     *
     * @return bool
     */
    protected function normalizeIsActive($isActive)
    {
        $isActive = strtolower($isActive);
        if ($isActive[0] === 't') {
            return true;
        }
        if ($isActive[0] === 'f') {
            return false;
        }

        $isActive = filter_var($isActive, FILTER_VALIDATE_BOOLEAN);

        return (bool)$isActive;
    }
}
