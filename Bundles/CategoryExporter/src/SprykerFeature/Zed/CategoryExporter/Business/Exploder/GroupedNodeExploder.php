<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CategoryExporter\Business\Exploder;

use SprykerFeature\Shared\CategoryExporter\Business\CategoryNodeKeyInterface;

class GroupedNodeExploder implements GroupedNodeExploderInterface
{

    /**
     * @param array $data
     * @param string $idsField
     * @param string $namesField
     * @param string $urlsField
     *
     * @return array
     */
    public function explodeGroupedNodes(array $data, $idsField, $namesField, $urlsField)
    {
        if (!$data[$idsField]) {
            return [];
        }

        $ids = explode(',', $data[$idsField]);
        $names = explode(',', $data[$namesField]);
        $urls = explode(',', $data[$urlsField]);
        $nodes = [];
        foreach ($ids as $key => $id) {
            $nodes[$id][CategoryNodeKeyInterface::NODE_ID] = $id;
            $nodes[$id][CategoryNodeKeyInterface::NAME] = $names[$key];
            $nodes[$id][CategoryNodeKeyInterface::URL] = $urls[$key];
        }

        return $nodes;
    }

}
