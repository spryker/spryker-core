<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Dependency\Facade;

interface CmsToTouchFacadeInterface
{
    /**
     * @param string $itemType
     * @param int $itemId
     * @param bool $keyChange
     *
     * @return bool
     */
    public function touchActive(string $itemType, int $itemId, bool $keyChange = false): bool;

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchInactive(string $itemType, int $itemId): bool;

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchDeleted(string $itemType, int $itemId): bool;
}
