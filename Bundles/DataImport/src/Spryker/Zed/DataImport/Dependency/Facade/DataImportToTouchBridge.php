<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency\Facade;

class DataImportToTouchBridge implements DataImportToTouchInterface
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
     * @param array $itemIds
     *
     * @return void
     */
    public function bulkTouchSetActive($itemType, array $itemIds)
    {
        $this->touchFacade->bulkTouchSetActive($itemType, $itemIds);
    }

    /**
     * @param string $itemType
     * @param int $itemId
     * @param bool $keyChange
     *
     * @return void
     */
    public function touchActive($itemType, $itemId, $keyChange = false)
    {
        $this->touchFacade->touchActive($itemType, $itemId, $keyChange);
    }

}
