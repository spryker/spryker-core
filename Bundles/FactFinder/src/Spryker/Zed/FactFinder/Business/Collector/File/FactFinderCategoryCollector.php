<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Collector\File;

use Pyz\Zed\Collector\Business\Storage\CategoryNodeCollector as StorageCategoryNodeCollector;
use Spryker\Zed\Collector\CollectorConfig;

class FactFinderCategoryCollector extends StorageCategoryNodeCollector
{

    /**
     * @param array $collectItemData
     *
     * @return array
     */
    protected function formatCategoryNode(array $collectItemData)
    {
        return [
            'ID' => $collectItemData[CollectorConfig::COLLECTOR_RESOURCE_ID],
            'parentID' => $collectItemData['parents'],
            'name' => $collectItemData['name'],
        ];
    }

    /**
     * @param array $node
     * @param array $data
     * @param bool $nested
     *
     * @return string
     */
    protected function getParents(array $node, array $data, $nested = true)
    {
        $parents = array_filter($data, function ($item) use ($node) {
            return ((int)$item['id_category_node'] === (int)$node['fk_parent_category_node']);
        });

        $result = 'ROOT';
        foreach ($parents as $index => $parent) {
            $result = $parent[CollectorConfig::COLLECTOR_RESOURCE_ID];
        }

        return $result;
    }

}
