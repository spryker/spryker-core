<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\CategoryExporter\Service\Builder;

use SprykerFeature\Client\Storage\Service\StorageClientInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class CategoryTreeBuilder
{

    const SUBTREE_DEPTH = 3;

    /**
     * @var StorageClientInterface
     */
    protected $kvReader;

    /**
     * @var KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param StorageClientInterface $kvReader
     * @param KeyBuilderInterface $keyBuilder
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
        $parents = array_slice(array_reverse($categoryNode['parents']), 0, self::SUBTREE_DEPTH);
        $subtree = [];
        $idCategoryCode = $categoryNode['node_id'];

        foreach ($parents as $parent) {
            $storageKey = $this->keyBuilder->generateKey(
                $parent['node_id'],
                $locale
            );
            $parentCategory = $this->kvReader->get($storageKey);

            if (isset($parentCategory['children'][$idCategoryCode])) {
                $parentCategory['children'][$idCategoryCode] = $categoryNode;
            }

            if (empty($subtree)) {
                $subtree = $parentCategory;
            }
            if ($parentCategory) {
                $parentCategory = $this->addCurrentSubtree($parentCategory, $subtree);
                $subtree = $parentCategory;
            }
        }

        if (empty($categoryNode['parents']) || empty($subtree)) {
            $subtree = $categoryNode;
        }

        $subtree['depth'] = self::SUBTREE_DEPTH;

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
        foreach ($parentCategory['children'] as $key => $child) {
            if ($child['url'] === $subtree['url']) {
                $parentCategory['children'][$key]['children'] = $subtree['children'];
            }
        }

        return $parentCategory;
    }

}
