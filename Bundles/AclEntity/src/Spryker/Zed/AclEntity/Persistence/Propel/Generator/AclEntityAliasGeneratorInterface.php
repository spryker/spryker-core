<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Generator;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface AclEntityAliasGeneratorInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param string $aliasToExtend
     *
     * @return string
     */
    public function generateTableAlias(ModelCriteria $query, string $aliasToExtend): string;

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param string $joinModel
     *
     * @return string
     */
    public function generateJoinAlias(ModelCriteria $query, string $joinModel): string;
}
