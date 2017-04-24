<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Touch\Helper;

use Codeception\Module;
use Codeception\Util\Shared\Asserts;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class TouchAssertionHelper extends Module
{

    use Asserts;
    use LocatorHelperTrait;

    /**
     * @param string $itemType
     * @param int $itemId
     * @param string $message
     *
     * @return void
     */
    public function assertTouchActive($itemType, $itemId, $message = '')
    {
        $touchEntity = $this->getTouchQueryContainer()
            ->queryTouchEntry($itemType, $itemId)
            ->findOne();

        $this->assertNotNull($touchEntity, $message);
        $this->assertSame(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, $touchEntity->getItemEvent(), $message);
    }

    /**
     * @param string $itemType
     * @param int $itemId
     * @param string $message
     *
     * @return void
     */
    public function assertTouchDeleted($itemType, $itemId, $message = '')
    {
        $touchEntity = $this->getTouchQueryContainer()
            ->queryTouchEntry($itemType, $itemId)
            ->findOne();

        $this->assertNotNull($touchEntity, $message);
        $this->assertSame(SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $touchEntity->getItemEvent(), $message);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    private function getTouchQueryContainer()
    {
        return $this->getLocator()->touch()->queryContainer();
    }

}
