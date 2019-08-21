<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Touch;

use Codeception\Actor;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class TouchBusinessTester extends Actor
{
    use _generated\TouchBusinessTesterActions;

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
