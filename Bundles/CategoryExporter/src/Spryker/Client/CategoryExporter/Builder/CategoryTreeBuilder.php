<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryExporter\Builder;

use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\CategoryExporter\Business\CategoryNodeKeyInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class CategoryTreeBuilder
{
    public const SUBTREE_DEPTH_KEY = 'depth';
    public const SUBTREE_DEPTH = 3;

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $kvReader;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $kvReader
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     */
    public function __construct(StorageClientInterface $kvReader, KeyBuilderInterface $keyBuilder)
    {
        $this->kvReader = $kvReader;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param array $categoryNode
     * @param string $locale
     *
     * @return array
     */
    public function createTreeFromCategoryNode(array $categoryNode, $locale)
    {
        $parents = array_slice(array_reverse($categoryNode[CategoryNodeKeyInterface::PARENTS]), 0, self::SUBTREE_DEPTH);
        $subtree = [];

        foreach ($parents as $parent) {
            $storageKey = $this->keyBuilder->generateKey(
                $parent[CategoryNodeKeyInterface::NODE_ID],
                $locale
            );
            $parentCategory = $this->kvReader->get($storageKey);

            if (empty($subtree)) {
                $subtree = $parentCategory;
            }
            if ($parentCategory) {
                $parentCategory = $this->addCurrentSubtree($parentCategory, $subtree);
                $subtree = $parentCategory;
            }
        }

        if (empty($categoryNode[CategoryNodeKeyInterface::PARENTS]) || empty($subtree)) {
            $subtree = $categoryNode;
        }

        $subtree[self::SUBTREE_DEPTH_KEY] = self::SUBTREE_DEPTH;

        return $subtree;
    }

    /**
     * @param array $parentCategory
     * @param array $subtree
     *
     * @return array
     */
    protected function addCurrentSubtree(array $parentCategory, array $subtree)
    {
        foreach ($parentCategory[CategoryNodeKeyInterface::CHILDREN] as $key => $child) {
            if ($child[CategoryNodeKeyInterface::URL] === $subtree[CategoryNodeKeyInterface::URL]) {
                $parentCategory[CategoryNodeKeyInterface::CHILDREN][$key][CategoryNodeKeyInterface::CHILDREN] =
                    $subtree[CategoryNodeKeyInterface::CHILDREN];
            }
        }

        return $parentCategory;
    }
}
