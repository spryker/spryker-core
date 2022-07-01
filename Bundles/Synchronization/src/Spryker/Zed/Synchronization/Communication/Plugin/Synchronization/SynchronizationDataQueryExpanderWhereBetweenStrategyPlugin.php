<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataQueryExpanderStrategyConfigurationTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryExpanderStrategyPluginInterface;

class SynchronizationDataQueryExpanderWhereBetweenStrategyPlugin implements SynchronizationDataQueryExpanderStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\SynchronizationDataQueryExpanderStrategyConfigurationTransfer $synchronizationDataQueryExpanderStrategyConfigurationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function expandQuery(
        ModelCriteria $query,
        SynchronizationDataQueryExpanderStrategyConfigurationTransfer $synchronizationDataQueryExpanderStrategyConfigurationTransfer
    ): ModelCriteria {
        $primaryKeys = $query->getTableMap()->getPrimaryKeys();

        $offset = $synchronizationDataQueryExpanderStrategyConfigurationTransfer->getOffsetOrFail();
        $chunkSize = $synchronizationDataQueryExpanderStrategyConfigurationTransfer->getChunkSizeOrFail();

        $nextRange = $offset + $chunkSize;

        /** @var literal-string $where */
        $where = sprintf('%s BETWEEN %s AND %s', key($primaryKeys), $offset, $nextRange);
        $query
            ->where($where);

        return $query;
    }
}
