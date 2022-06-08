<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Communication\Plugin\Event\Listener;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\AssetStorage\AssetStorageConfig;
use Spryker\Zed\AssetStorage\Communication\Exception\NoForeignKeyException;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Use {@link \Spryker\Zed\AssetStorage\Communication\Plugin\Publisher\Asset\AssetWritePublisherPlugin} instead.
 *
 * @method \Spryker\Zed\AssetStorage\Business\AssetStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\AssetStorage\AssetStorageConfig getConfig()
 * @method \Spryker\Zed\AssetStorage\Communication\AssetStorageCommunicationFactory getFactory()
 */
class AssetStoreStoragePublishListener extends AbstractPlugin implements EventHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer $eventEntityTransfer
     * @param string $eventName
     *
     * @throws \Spryker\Zed\AssetStorage\Communication\Exception\NoForeignKeyException
     *
     * @return void
     */
    public function handle(TransferInterface $eventEntityTransfer, $eventName)
    {
        $foreignKeys = $eventEntityTransfer->getForeignKeys();

        if (!isset($foreignKeys[AssetStorageConfig::COL_FK_ASSET])) {
            throw new NoForeignKeyException(AssetStorageConfig::COL_FK_ASSET);
        }
        if (!isset($foreignKeys[AssetStorageConfig::COL_FK_STORE])) {
            throw new NoForeignKeyException(AssetStorageConfig::COL_FK_STORE);
        }

        $idAsset = $foreignKeys[AssetStorageConfig::COL_FK_ASSET];
        $idStore = $foreignKeys[AssetStorageConfig::COL_FK_STORE];

        $this->getFacade()->publishStoreRelation($idAsset, $idStore);
    }
}
