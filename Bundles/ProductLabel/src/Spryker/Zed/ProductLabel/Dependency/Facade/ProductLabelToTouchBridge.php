<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Dependency\Facade;

class ProductLabelToTouchBridge implements ProductLabelToTouchInterface
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
    public function touchDeleted($itemType, $itemId)
    {
        return $this->touchFacade->touchDeleted($itemType, $itemId);
    }

}
