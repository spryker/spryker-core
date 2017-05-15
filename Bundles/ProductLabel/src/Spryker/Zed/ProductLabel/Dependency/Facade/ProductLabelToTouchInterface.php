<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Dependency\Facade;

interface ProductLabelToTouchInterface
{

    /**
     * @param string $itemType
     * @param int $itemId
     * @param bool $keyChange
     *
     * @return bool
     */
    public function touchActive($itemType, $itemId, $keyChange = false);

}
