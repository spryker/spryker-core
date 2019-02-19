<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorPersistenceFactory getFactory()
 */
class CmsBlockProductConnectorRepository extends AbstractRepository implements CmsBlockProductConnectorRepositoryInterface
{
    /***
     * @param int $idLocale
     * @param int $idCmsBlock
     *
     * @return array
     */
    public function getAssignedProductOptions(int $idLocale, int $idCmsBlock): array
    {
        $productAbstracts = $this->getFactory()->getCmsBlockProductConnectorToProductAbstractQueryContainer()
            ->queryProductAbstractWithName($idLocale)
            ->useSpyCmsBlockProductConnectorQuery()
                ->filterByFkCmsBlock($idCmsBlock)
            ->endUse()
            ->find();

        return $this->getFactory()
            ->createProductAbstractMapper()
            ->mapProductAbstractArrayToOptions($productAbstracts);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return array
     */
    public function getAssignedProductAbstractArray(int $idCmsBlock): array
    {
        return $this->getFactory()->createCmsBlockProductConnectorQuery()
            ->filterByFkCmsBlock($idCmsBlock)
            ->find()
            ->getColumnValues('fkProductAbstract');
    }
}
