<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Cms\Persistence\CmsPersistenceFactory getFactory()
 */
class CmsRepository extends AbstractRepository implements CmsRepositoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @param int $idCmsPage
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getRelatedStoresByIdCmsPage(int $idCmsPage): ArrayObject
    {
        $cmsPageStoreEntities = $this->getFactory()
            ->createCmsPageStoreQuery()
            ->filterByFkCmsPage($idCmsPage)
            ->leftJoinWithSpyStore()
            ->find();

        $relatedStores = new ArrayObject();

        foreach ($cmsPageStoreEntities as $cmsPageStoreEntity) {
            $storeTransfer = new StoreTransfer();
            $storeTransfer->fromArray($cmsPageStoreEntity->getSpyStore()->toArray(), true);
            $relatedStores->append($storeTransfer);
        }

        return $relatedStores;
    }
}
