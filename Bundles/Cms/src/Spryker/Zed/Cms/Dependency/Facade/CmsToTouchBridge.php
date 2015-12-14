<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cms\Dependency\Facade;

class CmsToTouchBridge implements CmsToTouchInterface
{

    /**
     * @var \Spryker\Zed\Touch\Business\TouchFacade
     */
    protected $touchFacade;

    /**
     * CmsToTouchBridge constructor.
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
     * @param bool $keyChange
     *
     * @return bool
     */
    public function touchActive($itemType, $itemId, $keyChange = false)
    {
        return $this->touchFacade->touchActive($itemType, $itemId, $keyChange);
    }

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchInactive($itemType, $itemId)
    {
        return $this->touchFacade->touchInactive($itemType, $itemId);
    }

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchDeleted($itemType, $itemId)
    {
        return $this->touchDeleted($itemType, $itemId);
    }
}
