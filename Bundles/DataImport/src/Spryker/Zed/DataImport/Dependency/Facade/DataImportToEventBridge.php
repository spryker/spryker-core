<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency\Facade;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;

class DataImportToEventBridge implements DataImportToEventInterface
{
    /**
     * @var EventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param EventFacadeInterface $eventFacade
     */
    public function __construct($eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     *
     * @return void
     */
    public function trigger($eventName, TransferInterface $eventTransfer)
    {
        $this->eventFacade->trigger($eventName, $eventTransfer);
    }
}
