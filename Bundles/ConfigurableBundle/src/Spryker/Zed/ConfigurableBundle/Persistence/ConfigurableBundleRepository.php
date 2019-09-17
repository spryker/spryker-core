<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundlePersistenceFactory getFactory()
 */
class ConfigurableBundleRepository extends AbstractRepository implements ConfigurableBundleRepositoryInterface
{
    /**
     * @param int $idProductList
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[]
     */
    public function findProductListUsageAmongSlots(int $idProductList): array
    {
        $configurableBundleTemplateSlotEntityCollection = $this->getFactory()
            ->createConfigurableBundleTemplateSlotQuery()
            ->joinWithSpyConfigurableBundleTemplate()
            ->filterByFkProductList($idProductList);

        if (!$configurableBundleTemplateSlotEntityCollection->count()) {
            return [];
        }

        $configurableBundleTemplateSlotTransferCollection = [];
        $configurableBundleMapper = $this->getFactory()->createConfigurableBundleMapper();

        foreach ($configurableBundleTemplateSlotEntityCollection as $configurableBundleTemplateSlotEntity) {
            $configurableBundleTemplateSlotTransferCollection[] = $configurableBundleMapper->mapConfigurableBundleTemplateSlotEntityToTransfer(
                $configurableBundleTemplateSlotEntity,
                new ConfigurableBundleTemplateSlotTransfer()
            );
        }

        return $configurableBundleTemplateSlotTransferCollection;
    }
}
