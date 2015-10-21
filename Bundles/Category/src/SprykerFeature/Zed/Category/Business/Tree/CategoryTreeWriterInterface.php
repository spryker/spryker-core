<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Tree;

use SprykerEngine\Zed\Kernel\Business\ModelResult;

/**
 * Interface CategoryTreeWriterInterface
 */
interface CategoryTreeWriterInterface
{

    /**
     * @param int $categoryId
     * @param string $locale
     * @param int|null $parentId
     * @param bool $createUrlPath
     *
     * @return int
     */
    public function createCategoryNode($categoryId, $locale, $parentId = null, $createUrlPath = true);

    /**
     * @param string $name
     * @param int $parentId
     * @param string $locale
     *
     * @return ModelResult
     */
    public function createNodeByCategoryName($name, $parentId, $locale);

    /**
     * @param int $nodeId
     * @param int $newParentId
     *
     * @return ModelResult
     */
    public function moveNode($nodeId, $newParentId);

    /**
     * @param int $nodeId
     * @param string $locale
     * @param bool $deleteChildren
     *
     * @return bool|int
     */
    public function deleteNode($nodeId, $locale, $deleteChildren = false);

}
