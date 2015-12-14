<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Category\Dependency\Facade;

use Generated\Shared\Transfer\TouchTransfer;

class CategoryToTouchBridge implements CategoryToTouchInterface
{

    /**
     * @var \Spryker\Zed\Touch\Business\TouchFacade
     */
    protected $touchFacade;

    /**
     * CategoryToTouchBridge constructor.
     *
     * @param \Spryker\Zed\Touch\Business\TouchFacade $touchFacade
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
     * @param string $itemType
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouchActive($itemType, array $itemIds = [])
    {
        return $this->touchFacade->bulkTouchActive($itemType, $itemIds);
    }

    /**
     * @param string $itemType
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouchInactive($itemType, array $itemIds = [])
    {
        return $this->touchFacade->bulkTouchInactive($itemType, $itemIds);
    }

    /**
     * @param string $itemType
     *
     * @return TouchTransfer[]
     */
    public function getItemsByType($itemType)
    {
        return $this->touchFacade->getItemsByType($itemType);
    }
}
