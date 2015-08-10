<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CategoryExporter\Business\Formatter;

use SprykerFeature\Zed\CategoryExporter\Business\CategoryNodeKeyInterface;
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

        /**
         * @todo: Replace "Constant Interface" with a CategoryNode representation object or a Transfer object.
         */
        return [
            CategoryNodeKeyInterface::NODE_ID => $categoryNode[CategoryNodeKeyInterface::NODE_ID],
            CategoryNodeKeyInterface::NAME => $categoryNode['category_name'],
            CategoryNodeKeyInterface::URL => $categoryUrls[0],
            CategoryNodeKeyInterface::IMAGE => $categoryNode['category_image_name'],
            CategoryNodeKeyInterface::CHILDREN => $this->nodeExploder->explodeGroupedNodes(
                $categoryNode,
                'category_child_ids',
                'category_child_names',
                'category_child_urls'
            ),
            CategoryNodeKeyInterface::PARENTS => $this->nodeExploder->explodeGroupedNodes(
                $categoryNode,
                'category_parent_ids',
                'category_parent_names',
                'category_parent_urls'
            ),
        ];
    }

}
