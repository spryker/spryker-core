<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundlePersistenceFactory getFactory()
 */
class ConfigurableBundleRepository extends AbstractRepository implements ConfigurableBundleRepositoryInterface
{
    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer|null
     */
    public function findConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): ?ConfigurableBundleTemplateTransfer
    {
        $configurableBundleTemplateEntity = $this->getFactory()
            ->createConfigurableBundleTemplateQuery()
            ->leftJoinWithSpyConfigurableBundleTemplateSlot()
            ->filterByIdConfigurableBundleTemplate($idConfigurableBundleTemplate)
            ->find()
            ->getFirst();

        if (!$configurableBundleTemplateEntity) {
            return null;
        }

        return $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapConfigurableBundleTemplateEntityToTransfer($configurableBundleTemplateEntity, new ConfigurableBundleTemplateTransfer());
    }

    /**
     * @param string[] $allowedTemplateUuids
     *
     * @return string[]
     */
    public function getActiveConfigurableBundleTemplateUuids(array $allowedTemplateUuids): array
    {
        return $this->getFactory()
            ->createConfigurableBundleTemplateQuery()
            ->select([SpyConfigurableBundleTemplateTableMap::COL_UUID])
            ->filterByUuid_In($allowedTemplateUuids)
            ->filterByIsActive(true)
            ->find()
            ->toArray();
    }
}
