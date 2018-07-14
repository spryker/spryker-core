<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence;

use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValuePersistenceFactory getFactory()
 */
class MinimumOrderValueRepository extends AbstractRepository implements MinimumOrderValueRepositoryInterface
{
    /**
     * @param string $minimumOrderValueTypeName
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function findMinimumOrderValueTypeByName(
        string $minimumOrderValueTypeName
    ): MinimumOrderValueTypeTransfer {
        $minimumOrderValueTypeEntity = $this->getFactory()
            ->createMinimumOrderValueTypeQuery()
            ->filterByName($minimumOrderValueTypeName)
            ->findOne();

        $minimumOrderValueTypeTransfer = $this->getFactory()
            ->createMinimumOrderValueMapper()
            ->mapMinimumOrderValueTypeTransfer(
                $minimumOrderValueTypeEntity,
                new MinimumOrderValueTypeTransfer()
            );

        return $minimumOrderValueTypeTransfer;
    }
}
