<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ConfigurableBundleCart\Persistence\ConfigurableBundleCartPersistenceFactory getFactory()
 */
class ConfigurableBundleCartRepository extends AbstractRepository implements ConfigurableBundleCartRepositoryInterface
{
    /**
     * @param string $configurableBundleTemplateUuid
     * @param string[] $configurableBundleTemplateSlotUuids
     *
     * @return bool
     */
    public function verifyConfigurableBundleTemplateSlots(string $configurableBundleTemplateUuid, array $configurableBundleTemplateSlotUuids): bool
    {
        $count = $this->getFactory()
            ->getConfigurableBundleTemplateSlotPropelQuery()
            ->filterByUuid_In($configurableBundleTemplateSlotUuids)
            ->useSpyConfigurableBundleTemplateQuery()
                ->filterByUuid($configurableBundleTemplateUuid)
            ->endUse()
            ->count();

        return $count === count($configurableBundleTemplateSlotUuids);
    }
}
