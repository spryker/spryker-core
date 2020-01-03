<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use Orm\Zed\Cms\Persistence\SpyCmsPageStore;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Cms\Persistence\CmsPersistenceFactory getFactory()
 */
class CmsEntityManager extends AbstractEntityManager implements CmsEntityManagerInterface
{
    /**
     * {@inheritDoc}
     *
     * @param array $idStores
     * @param int $idCmsPage
     *
     * @return void
     */
    public function addStoreRelations(array $idStores, int $idCmsPage): void
    {
        foreach ($idStores as $idStore) {
            $cmsPageStoreEntity = new SpyCmsPageStore();
            $cmsPageStoreEntity->setFkStore($idStore)
                ->setFkCmsPage($idCmsPage)
                ->save();
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param array $idStores
     * @param int $idCmsPage
     *
     * @return void
     */
    public function removeStoreRelations(array $idStores, int $idCmsPage): void
    {
        if (empty($idStores)) {
            return;
        }

        $this->getFactory()
            ->createCmsPageStoreQuery()
            ->filterByFkCmsPage($idCmsPage)
            ->filterByFkStore_In($idStores)
            ->delete();
    }
}
