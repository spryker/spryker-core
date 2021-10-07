<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Persistence;

interface TouchEntityManagerInterface
{
    /**
     * @param array<int> $touchEntityIds
     *
     * @return int
     */
    public function deleteTouchEntitiesByIds(array $touchEntityIds): int;

    /**
     * @param array<int> $touchEntityIds
     *
     * @return int
     */
    public function deleteTouchSearchEntitiesByTouchIds(array $touchEntityIds): int;

    /**
     * @param array<int> $touchEntityIds
     *
     * @return int
     */
    public function deleteTouchStorageEntitiesByTouchIds(array $touchEntityIds): int;
}
