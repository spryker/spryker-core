<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency\Facade;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

class DataImportToEventBridge implements DataImportToEventInterface
{

    /**
     * @var \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    protected $facadeEvent;

    /**
     * @param \Spryker\Zed\Event\Business\EventFacadeInterface $facadeEvent
     */
    public function __construct($facadeEvent)
    {
        $this->facadeEvent = $facadeEvent;
    }

    /**
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     *
     * @return void
     */
    public function trigger($eventName, TransferInterface $eventTransfer)
    {
        $this->facadeEvent->trigger($eventName, $eventTransfer);
    }

}
