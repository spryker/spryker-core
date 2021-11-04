<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Dependency\Facade;

interface PriceProductToTouchFacadeInterface
{
    /**
     * @param string $itemType
     * @param int $idItem
     * @param bool $keyChange
     *
     * @return bool
     */
    public function touchActive($itemType, $idItem, $keyChange = false);

    /**
     * @param string $itemType
     * @param int $idItem
     *
     * @return bool
     */
    public function touchDeleted($itemType, $idItem);

    /**
     * @param string $itemType
     * @param array<int> $itemIds
     *
     * @return int
     */
    public function bulkTouchActive($itemType, array $itemIds);

    /**
     * @param string $itemType
     * @param array<int> $itemIds
     *
     * @return int
     */
    public function bulkTouchDeleted($itemType, array $itemIds);
}
