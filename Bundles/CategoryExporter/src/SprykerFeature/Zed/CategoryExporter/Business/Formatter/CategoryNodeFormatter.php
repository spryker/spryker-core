<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CategoryExporter\Business\Formatter;

use SprykerFeature\Zed\CategoryExporter\Business\Exploder\GroupedNodeExploderInterface;

class CategoryNodeFormatter implements CategoryNodeFormatterInterface
{

    /**
     * @var GroupedNodeExploderInterface
     */
    protected $nodeExploder;

    /**
     * @param GroupedNodeExploderInterface $nodeExploder
     */
    public function __construct(GroupedNodeExploderInterface $nodeExploder)
    {
        $this->nodeExploder = $nodeExploder;
    }

    /**
     * @param array $categoryNode
     *
     * @return array
     */
    public function formatCategoryNode(array $categoryNode)
    {
        $categoryUrls = explode(',', $categoryNode['category_urls']);

        return [
            'node_id' => $categoryNode['node_id'],
            'name' => $categoryNode['category_name'],
            'url' => $categoryUrls[0],
            'image' => $categoryNode['category_image_name'],
            'children' => $this->nodeExploder->explodeGroupedNodes(
                $categoryNode,
                'category_child_ids',
                'category_child_names',
                'category_child_urls'
            ),
            'parents' => $this->nodeExploder->explodeGroupedNodes(
                $categoryNode,
                'category_parent_ids',
                'category_parent_names',
                'category_parent_urls'
            ),
        ];
    }

}
