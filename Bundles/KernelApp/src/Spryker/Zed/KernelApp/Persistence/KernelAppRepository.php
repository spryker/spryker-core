<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\KernelApp\Persistence;

use DateTime;
use Generated\Shared\Transfer\AppConfigTransfer;
use Orm\Zed\KernelApp\Persistence\Map\SpyAppConfigTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\KernelApp\Persistence\KernelAppPersistenceFactory getFactory()
 */
class KernelAppRepository extends AbstractRepository implements KernelAppRepositoryInterface
{
    /**
     * @param int $gracePeriod
     *
     * @return list<\Generated\Shared\Transfer\AppConfigTransfer>
     */
    public function getActiveAppConfigs(int $gracePeriod): array
    {
        $appConfigQuery = $this->getFactory()->createAppConfigPropelQuery();

        $criterion = $appConfigQuery->getNewCriterion(
            SpyAppConfigTableMap::COL_IS_ACTIVE,
            true,
            Criteria::EQUAL,
        );

        // If the grace period is defined, we check whether the App config became inactive during the grace period and add it to the result set.
        // If the `SpyAppConfigTableMap::COL_UPDATED_AT` constant is not defined, this check is skipped because the column does not exist in the database.
        if ($gracePeriod && defined(sprintf('%s::COL_UPDATED_AT', SpyAppConfigTableMap::class))) {
            $criterion->addOr(
                $appConfigQuery->getNewCriterion(
                    SpyAppConfigTableMap::COL_UPDATED_AT,
                    new DateTime(sprintf('-%s seconds', $gracePeriod)),
                    Criteria::GREATER_EQUAL,
                )->addAnd(
                    $appConfigQuery->getNewCriterion(
                        SpyAppConfigTableMap::COL_IS_ACTIVE,
                        false,
                        Criteria::EQUAL,
                    ),
                ),
            );
        }

        $appConfigQuery->add($criterion);
        $appConfigEntities = $appConfigQuery->find();

        $appConfigMapper = $this->getFactory()->createAppConfigMapper();

        $appConfigTransfers = [];

        foreach ($appConfigEntities as $appConfigEntity) {
            $appConfigTransfers[] = $appConfigMapper
                ->mapAppConfigEntityToAppConfigTransfer($appConfigEntity, new AppConfigTransfer());
        }

        return $appConfigTransfers;
    }
}
