<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategorySearch\Business\External;

/**
 * Interface CategoryTreeInterface
 */
interface ProductCategorySearchToCategoryTreeInterface
{

    /**
     * @param int    $nodeId
     * @param string $locale
     * @param bool   $excludeStartNode
     * @param bool   $onlyParents
     *
     * @return mixed
     */
    public function getGroupedPathIds($nodeId, $locale, $excludeStartNode = true, $onlyParents = false);

}
