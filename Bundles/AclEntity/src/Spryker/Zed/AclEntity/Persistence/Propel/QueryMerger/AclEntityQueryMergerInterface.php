<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface AclEntityQueryMergerInterface
{
    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $dstQuery
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $srcQuery
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $dstQuery
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $srcQuery
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mergeQueries(ModelCriteria $dstQuery, ModelCriteria $srcQuery): ModelCriteria;
}
