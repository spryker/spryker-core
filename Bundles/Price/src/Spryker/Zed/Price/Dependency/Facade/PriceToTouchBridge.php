<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Dependency\Facade;

use Spryker\Zed\Touch\Business\TouchFacade;

class PriceToTouchBridge implements PriceToTouchInterface
{

    /**
     * @var TouchFacade
     */
    protected $touchFacade;

    /**
     * ProductCategoryToTouchBridge constructor.
     *
     * @param TouchFacade $touchFacade
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
     * @param string $itemId
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
    public function bulkTouchActive($itemType, array $itemIds)
    {
        return $this->touchFacade->bulkTouchActive($itemType, $itemIds);
    }

    /**
     * @param string $itemType
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouchDeleted($itemType, array $itemIds)
    {
        return $this->touchFacade->bulkTouchDeleted($itemType, $itemIds);
    }

}
