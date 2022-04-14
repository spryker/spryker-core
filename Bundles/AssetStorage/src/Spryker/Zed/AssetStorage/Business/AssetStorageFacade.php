<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AssetStorage\Business\AssetStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStorageRepositoryInterface getRepository()
 */
class AssetStorageFacade extends AbstractFacade implements AssetStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idAsset
     *
     * @return void
     */
    public function publish(int $idAsset): void
    {
        $this->getFactory()
            ->createAssetStorageWriter()
            ->publish($idAsset);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idAsset
     * @param int $idStore
     *
     * @return void
     */
    public function publishStoreRelation(int $idAsset, int $idStore): void
    {
        $this->getFactory()
            ->createAssetStorageWriter()
            ->publishStoreRelation($idAsset, $idStore);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idAsset
     *
     * @return void
     */
    public function unpublish(int $idAsset): void
    {
        $this->getFactory()
            ->createAssetStorageWriter()
            ->unpublish($idAsset);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idAsset
     * @param int $idStore
     *
     * @return void
     */
    public function unpublishStoreRelation(int $idAsset, int $idStore): void
    {
        $this->getFactory()
            ->createAssetStorageWriter()
            ->unpublishStoreRelation($idAsset, $idStore);
    }
}
