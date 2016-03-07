<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Dependency\Facade;

use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Oms\Business\OmsFacade;

class RefundToOmsBridge implements RefundToOmsInterface
{

    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacade
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacade $omsFacade
     */
    public function __construct($omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param string $eventId
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return void
     */
    public function triggerEvent($eventId, ObjectCollection $orderItems, array $logContext, array $data = [])
    {
        $this->omsFacade->triggerEvent($eventId, $orderItems, $logContext, $data);
    }

}
