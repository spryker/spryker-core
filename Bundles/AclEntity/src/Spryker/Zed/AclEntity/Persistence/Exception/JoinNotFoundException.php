<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Exception;

use Propel\Runtime\ActiveQuery\ModelCriteria;

class JoinNotFoundException extends AclEntityException
{
    /**
     * @var string
     */
    protected const MESSAGE_TEMPLATE = 'Failed to find "%s" table join in the query object: %s';

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param string $tableName
     */
    public function __construct(ModelCriteria $query, string $tableName)
    {
        parent::__construct(sprintf(static::MESSAGE_TEMPLATE, $tableName, $query->toString()));
    }
}
