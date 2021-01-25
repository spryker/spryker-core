<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Dependency\Facade;

interface StockToTouchInterface
{
    /**
     * @param string $itemType
     * @param int $idItem
     * @param bool $keyChange
     *
     * @return bool
     */
    public function touchActive($itemType, $idItem, $keyChange = false);
}
