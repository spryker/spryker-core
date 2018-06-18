<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Touch\Helper;

use Codeception\Module;
use Codeception\Util\Shared\Asserts;
use DateTime;
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
     * @param string $itemType
     * @param int $itemId
     * @param \DateTime $dateTime
     * @param string $message
     *
     * @return void
     */
    public function assertTouchActiveAfter($itemType, $itemId, DateTime $dateTime, $message = '')
    {
        $this->assertTouchActive($itemType, $itemId, $message);
        $this->assertTouchAfter($itemType, $itemId, $dateTime, $message);
    }

    /**
     * @param string $itemType
     * @param int $itemId
     * @param \DateTime $dateTime
     * @param string $message
     *
     * @return void
     */
    public function assertTouchDeletedAfter($itemType, $itemId, DateTime $dateTime, $message = '')
    {
        $this->assertTouchDeleted($itemType, $itemId, $message);
        $this->assertTouchAfter($itemType, $itemId, $dateTime, $message);
    }

    /**
     * @param string $itemType
     * @param int $itemId
     * @param \DateTime $dateTime
     * @param string $message
     *
     * @return void
     */
    protected function assertTouchAfter($itemType, $itemId, DateTime $dateTime, $message)
    {
        $touchEntity = $this->getTouchQueryContainer()
            ->queryTouchEntry($itemType, $itemId)
            ->findOne();

        $this->assertGreaterThanOrEqual(
            $dateTime->getTimestamp(),
            $touchEntity->getTouched()->getTimestamp(),
            $message
        );
    }

    /**
     * @param string $itemType
     * @param int $itemId
     * @param \DateTime $dateTime
     * @param string $message
     *
     * @return void
     */
    public function assertNoTouchAfter($itemType, $itemId, DateTime $dateTime, $message = '')
    {
        $touchEntity = $this->getTouchQueryContainer()
            ->queryTouchEntry($itemType, $itemId)
            ->findOne();

        $this->assertNotNull($touchEntity, $message);
        $this->assertLessThanOrEqual(
            $dateTime->getTimestamp(),
            $touchEntity->getTouched()->getTimestamp(),
            $message
        );
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getLocator()->touch()->queryContainer();
    }
}
