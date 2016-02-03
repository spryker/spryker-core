<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Dependency\Facade;

use Spryker\Zed\Touch\Business\TouchFacade;

class ProductToTouchBridge implements ProductToTouchInterface
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

}
