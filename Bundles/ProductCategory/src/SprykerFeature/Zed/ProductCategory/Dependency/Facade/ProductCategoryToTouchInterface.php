<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Dependency\Facade;

interface ProductCategoryToTouchInterface
{
    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchActive($itemType, $itemId);

    /**
     * @param $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchDeleted($itemType, $itemId);
}
