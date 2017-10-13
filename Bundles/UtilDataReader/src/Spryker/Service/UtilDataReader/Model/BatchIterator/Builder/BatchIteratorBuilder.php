<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader\Model\BatchIterator\Builder;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Service\UtilDataReader\Model\BatchIterator\PropelBatchIterator;

class BatchIteratorBuilder implements BatchIteratorBuilderInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\PropelBatchIterator
     */
    public function buildPropelBatchIterator(ModelCriteria $query, $chunkSize = 100)
    {
        return new PropelBatchIterator($query, $chunkSize);
    }
}
