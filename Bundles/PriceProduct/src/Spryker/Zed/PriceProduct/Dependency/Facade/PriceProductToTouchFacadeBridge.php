<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Dependency\Facade;

class PriceProductToTouchFacadeBridge implements PriceProductToTouchFacadeInterface
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
     * @param int $idItem
     * @param bool $keyChange
     *
     * @return bool
     */
    public function touchActive($itemType, $idItem, $keyChange = false)
    {
        return $this->touchFacade->touchActive($itemType, $idItem, $keyChange);
    }

    /**
     * @param string $itemType
     * @param int $idItem
     *
     * @return bool
     */
    public function touchDeleted($itemType, $idItem)
    {
        return $this->touchFacade->touchDeleted($itemType, $idItem);
    }

    /**
     * @phpstan-param array<int> $itemIds
     *
     * @param string $itemType
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouchActive($itemType, array $itemIds)
    {
        return $this->touchFacade->bulkTouchSetActive($itemType, $itemIds);
    }

    /**
     * @phpstan-param array<int> $itemIds
     *
     * @param string $itemType
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouchDeleted($itemType, array $itemIds)
    {
        return $this->touchFacade->bulkTouchSetDeleted($itemType, $itemIds);
    }
}
