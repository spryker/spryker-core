<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Dependency\Facade;

interface CmsToTouchInterface
{
    /**
     * @param string $itemType
     * @param int $itemId
     * @param bool $keyChange
     *
     * @return bool
     */
    public function touchActive($itemType, $itemId, $keyChange = false);

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchInactive($itemType, $itemId);

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchDeleted($itemType, $itemId);
}
