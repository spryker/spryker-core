<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Dependency\Facade;

class SalesOmsToOmsFacadeBridge implements SalesOmsToOmsFacadeInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacadeInterface $omsFacade
     */
    public function __construct($omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param string $eventId
     * @param int $orderItemId
     * @param array $data
     *
     * @return array|null
     */
    public function triggerEventForOneOrderItem($eventId, $orderItemId, array $data = [])
    {
        return $this->omsFacade->triggerEventForOneOrderItem($eventId, $orderItemId, $data);
    }

    /**
     * @param int $idOrderItem
     *
     * @return string[]
     */
    public function getManualEvents($idOrderItem)
    {
        return $this->omsFacade->getManualEvents($idOrderItem);
    }
}
