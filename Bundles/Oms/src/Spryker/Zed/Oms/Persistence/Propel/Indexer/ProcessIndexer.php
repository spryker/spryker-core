<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence\Propel\Indexer;

class ProcessIndexer implements ProcessIndexerInterface
{
    /**
     * @param array<\Orm\Zed\Oms\Persistence\SpyOmsOrderProcess> $omsOrderProcessEntities
     *
     * @return array<int, string>
     */
    public function getProcessNamesIndexedByIdOmsOrderProcess(array $omsOrderProcessEntities): array
    {
        $processes = [];
        foreach ($omsOrderProcessEntities as $omsOrderProcessEntity) {
            $processes[$omsOrderProcessEntity->getIdOmsOrderProcess()] = $omsOrderProcessEntity->getName();
        }

        return $processes;
    }
}
