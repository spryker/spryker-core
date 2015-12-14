<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Dependency\Facade;

class ProductCategoryToTouchBridge implements ProductCategoryToTouchInterface
{

    /**
     * @var \Spryker\Zed\Touch\Business\TouchFacade
     */
    protected $touchFacade;

    /**
     * ProductCategoryToTouchBridge constructor.
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
     * @param $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchDeleted($itemType, $itemId)
    {
        return $this->touchFacade->touchDeleted($itemType, $itemId);
    }
}
