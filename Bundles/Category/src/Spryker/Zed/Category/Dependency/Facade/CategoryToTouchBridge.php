<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Dependency\Facade;

class CategoryToTouchBridge implements CategoryToTouchInterface
{
    /**
     * @var \Spryker\Zed\Touch\Business\TouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\Touch\Business\TouchFacadeInterface $touchFacade
     */
    public function __construct($touchFacade)
    {
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchActive($itemType, $itemId)
    {
        return $this->touchFacade->touchActive($itemType, $itemId);
    }

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchDeleted($itemType, $itemId)
    {
        return $this->touchFacade->touchDeleted($itemType, $itemId);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @param string $itemType
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouchActive($itemType, array $itemIds = [])
    {
        // BC reason
        if (method_exists($this->touchFacade, 'bulkTouchActive')) {
            return $this->touchFacade->bulkTouchActive($itemType, $itemIds);
        }

        return $this->touchFacade->bulkTouchSetActive($itemType, $itemIds);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @param string $itemType
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouchInactive($itemType, array $itemIds = [])
    {
        // BC reason
        if (method_exists($this->touchFacade, 'bulkTouchInactive')) {
            return $this->touchFacade->bulkTouchInactive($itemType, $itemIds);
        }

        return $this->touchFacade->bulkTouchSetInActive($itemType, $itemIds);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @param string $itemType
     *
     * @return \Generated\Shared\Transfer\TouchTransfer[]
     */
    public function getItemsByType($itemType)
    {
        return $this->touchFacade->getItemsByType($itemType);
    }
}
