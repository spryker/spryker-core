<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\Mapper;

use Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

interface ColumnQueryMapperInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer $columnSelectionTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapColumns(ModelCriteria $query, PropelQueryBuilderColumnSelectionTransfer $columnSelectionTransfer);
}
