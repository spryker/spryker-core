<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Generator;

use Propel\Runtime\ActiveQuery\ModelCriteria;

class AclEntityAliasGenerator implements AclEntityAliasGeneratorInterface
{
    /**
     * @var string
     */
    protected const SUFFIX_TABLE = '_acl';

    /**
     * @var string
     */
    protected const SUFFIX_MODEL = 'Acl';

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param string $aliasToExtend
     *
     * @return string
     */
    public function generateTableAlias(ModelCriteria $query, string $aliasToExtend): string
    {
        $tableAlias = $aliasToExtend . static::SUFFIX_TABLE;
        if (!$this->hasAlias($query, $tableAlias)) {
            return $tableAlias;
        }
        $i = 1;
        while ($this->hasAlias($query, $tableAlias . $i)) {
            $i++;
        }

        return $tableAlias . $i;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param string $joinModel
     *
     * @return string
     */
    public function generateJoinAlias(ModelCriteria $query, string $joinModel): string
    {
        $modelAlias = $joinModel . static::SUFFIX_MODEL;
        if (!$query->hasJoin($modelAlias)) {
            return $modelAlias;
        }
        $i = 1;
        while ($query->hasJoin($modelAlias . $i)) {
            $i++;
        }

        return $modelAlias . $i;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param string $alias
     *
     * @return bool
     */
    protected function hasAlias(ModelCriteria $query, string $alias): bool
    {
        return $query->getTableForAlias($alias) !== null;
    }
}
