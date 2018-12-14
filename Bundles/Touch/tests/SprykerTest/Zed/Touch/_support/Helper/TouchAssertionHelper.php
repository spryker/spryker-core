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
use Orm\Zed\Touch\Persistence\SpyTouch;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;
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
    public function assertNoTouchEntry(string $itemType, int $itemId, string $message = ''): void
    {
        $touchEntity = $this->getTouchQueryContainer()
            ->queryUpdateTouchEntry($itemType, $itemId)
            ->findOne();

        $this->assertNull($touchEntity, $message);
    }

    /**
     * @param string $itemType
     * @param int $itemId
     * @param string $message
     *
     * @return void
     */
    public function assertTouchActive(string $itemType, int $itemId, string $message = ''): void
    {
        $touchEntity = $this->getTouchQueryContainer()
            ->queryUpdateTouchEntry($itemType, $itemId, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
            ->findOne();

        $this->assertNotNull($touchEntity, $message);
        $this->assertSame(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, $touchEntity->getItemEvent(), $message);
        $this->assertTouchSame($touchEntity, $itemType, $itemId);
    }

    /**
     * @param string $itemType
     * @param int $itemId
     * @param string $message
     *
     * @return void
     */
    public function assertTouchInactive(string $itemType, int $itemId, string $message = ''): void
    {
        $touchEntity = $this->getTouchQueryContainer()
            ->queryUpdateTouchEntry($itemType, $itemId, SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE)
            ->findOne();

        $this->assertNotNull($touchEntity, $message);
        $this->assertSame(SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE, $touchEntity->getItemEvent(), $message);
        $this->assertTouchSame($touchEntity, $itemType, $itemId);
    }

    /**
     * @param string $itemType
     * @param int $itemId
     * @param string $message
     *
     * @return void
     */
    public function assertTouchDeleted(string $itemType, int $itemId, string $message = ''): void
    {
        $touchEntity = $this->getTouchQueryContainer()
            ->queryUpdateTouchEntry($itemType, $itemId, SpyTouchTableMap::COL_ITEM_EVENT_DELETED)
            ->findOne();

        $this->assertNotNull($touchEntity, $message);
        $this->assertSame(SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $touchEntity->getItemEvent(), $message);
        $this->assertTouchSame($touchEntity, $itemType, $itemId);
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouch $touchEntity
     * @param string $itemType
     * @param int $itemId
     *
     * @return void
     */
    protected function assertTouchSame(SpyTouch $touchEntity, string $itemType, int $itemId): void
    {
        $this->assertSame($itemType, $touchEntity->getItemType());
        $this->assertSame($itemId, $touchEntity->getItemId());
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
    public function assertTouchDeletedAfter(string $itemType, int $itemId, DateTime $dateTime, string $message = ''): void
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
    protected function assertTouchAfter(string $itemType, int $itemId, DateTime $dateTime, string $message): void
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
    public function assertNoTouchAfter(string $itemType, int $itemId, DateTime $dateTime, string $message = ''): void
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
    protected function getTouchQueryContainer(): TouchQueryContainerInterface
    {
        return $this->getLocator()->touch()->queryContainer();
    }
}
