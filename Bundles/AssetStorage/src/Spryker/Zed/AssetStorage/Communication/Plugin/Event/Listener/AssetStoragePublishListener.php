<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Communication\Plugin\Event\Listener;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Use {@link \Spryker\Zed\AssetStorage\Communication\Plugin\Publisher\Asset\AssetWritePublisherPlugin} instead.
 *
 * @method \Spryker\Zed\AssetStorage\Business\AssetStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\AssetStorage\AssetStorageConfig getConfig()
 * @method \Spryker\Zed\AssetStorage\Communication\AssetStorageCommunicationFactory getFactory()
 */
class AssetStoragePublishListener extends AbstractPlugin implements EventHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer $transfer
     * @param string $eventName
     *
     * @return void
     */
    public function handle(TransferInterface $transfer, $eventName)
    {
        $this->getFacade()->publish($transfer->getId());
    }
}
