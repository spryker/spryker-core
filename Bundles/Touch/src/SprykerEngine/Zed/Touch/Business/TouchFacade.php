<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Touch\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;

/**
 * @method TouchDependencyContainer getDependencyContainer()
 */
class TouchFacade extends AbstractFacade
{

    /**
     * @param string $itemType
     * @param int $idItem
     *
     * @return bool
     */
    public function touchActive($itemType, $idItem)
    {
        $touchRecordModel = $this->getDependencyContainer()->getTouchRecordModel();

        return $touchRecordModel->saveTouchRecord(
            $itemType,
            SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE,
            $idItem
        );
    }

    /**
     * @param string $itemType
     * @param int $idItem
     *
     * @return bool
     */
    public function touchInactive($itemType, $idItem)
    {
        $touchRecordModel = $this->getDependencyContainer()->getTouchRecordModel();

        return $touchRecordModel->saveTouchRecord(
            $itemType,
            SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE,
            $idItem
        );
    }

    /**
     * @param string $itemType
     * @param int $idItem
     *
     * @return bool
     */
    public function touchDeleted($itemType, $idItem)
    {
        $touchRecordModel = $this->getDependencyContainer()->getTouchRecordModel();

        return $touchRecordModel->saveTouchRecord(
            $itemType,
            SpyTouchTableMap::COL_ITEM_EVENT_DELETED,
            $idItem
        );
    }

}
