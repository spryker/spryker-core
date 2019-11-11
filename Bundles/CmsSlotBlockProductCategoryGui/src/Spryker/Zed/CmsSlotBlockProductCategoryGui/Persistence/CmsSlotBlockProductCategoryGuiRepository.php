<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CmsSlotBlockProductCategoryGui\Persistence\CmsSlotBlockProductCategoryGuiPersistenceFactory getFactory()
 */
class CmsSlotBlockProductCategoryGuiRepository extends AbstractRepository implements CmsSlotBlockProductCategoryGuiRepositoryInterface
{
    /**
     * @param int[]|null $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function getProductAbstracts(?array $productAbstractIds = []): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        $idLocale = $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getIdLocale();

        $productAbstractCollection = $this->getFactory()->getProductQueryContainer()
            ->queryProductAbstractWithName($idLocale)
            ->filterByIdProductAbstract_In($productAbstractIds)
            ->find();

        return $this->getFactory()->createCmsSlotBlockProductCategoryGuiMapper()
            ->mapProductAbstractCollectionToTransfers($productAbstractCollection);
    }
}
