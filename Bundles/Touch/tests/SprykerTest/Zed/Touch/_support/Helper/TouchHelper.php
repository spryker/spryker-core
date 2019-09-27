<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Touch\Helper;

use Codeception\Module;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;

class TouchHelper extends Module
{
    /**
     * @return int
     */
    public function getTouchEntitiesForDeletedItemEventCount(): int
    {
        return SpyTouchQuery::create()
            ->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_DELETED)
            ->count();
    }
}
