<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataQueryExpanderStrategyConfigurationTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryExpanderStrategyPluginInterface;

class SynchronizationDataQueryExpanderOffsetLimitStrategyPlugin implements SynchronizationDataQueryExpanderStrategyPluginInterface
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
        $query
            ->offset($synchronizationDataQueryExpanderStrategyConfigurationTransfer->getOffsetOrFail())
            ->limit($synchronizationDataQueryExpanderStrategyConfigurationTransfer->getChunkSizeOrFail());

        return $query;
    }
}
