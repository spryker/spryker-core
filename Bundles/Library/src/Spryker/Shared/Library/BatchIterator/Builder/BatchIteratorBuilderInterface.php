<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\BatchIterator\Builder;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface BatchIteratorBuilderInterface
{

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Spryker\Shared\Library\BatchIterator\PropelBatchIterator
     */
    public function buildPropelBatchIterator(ModelCriteria $query);

}
