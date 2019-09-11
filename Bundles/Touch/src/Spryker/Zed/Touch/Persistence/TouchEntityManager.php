<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Touch\Persistence\TouchPersistenceFactory getFactory()
 */
class TouchEntityManager extends AbstractEntityManager implements TouchEntityManagerInterface
{
    /**
     * @param int[] $touchEntityIds
     *
     * @return int
     */
    public function deleteTouchEntitiesByIds(array $touchEntityIds): int
    {
        return $this->getFactory()
            ->createTouchQuery()
            ->filterByIdTouch_In($touchEntityIds)
            ->delete();
    }

    /**
     * @param int[] $touchEntityIds
     *
     * @return int
     */
    public function deleteTouchSearchEntitiesByTouchIds(array $touchEntityIds): int
    {
        return $this->getFactory()
            ->createTouchSearchQuery()
            ->filterByFkTouch_In($touchEntityIds)
            ->delete();
    }

    /**
     * @param int[] $touchEntityIds
     *
     * @return int
     */
    public function deleteTouchStorageEntitiesByTouchIds(array $touchEntityIds): int
    {
        return $this->getFactory()
            ->createTouchStorageQuery()
            ->filterByFkTouch_In($touchEntityIds)
            ->delete();
    }
}
