<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Exception;

class TablePrimaryKeyNotFoundException extends AclEntityException
{
    /**
     * @var string
     */
    protected const MESSAGE_TEMPLATE = 'Failed to find table primary key: %s';

    /**
     * @param string $table
     */
    public function __construct(string $table)
    {
        parent::__construct(sprintf(static::MESSAGE_TEMPLATE, $table));
    }
}
