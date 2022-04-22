<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Communication\Exception;

use Propel\Runtime\Exception\PropelException;

class NoForeignKeyException extends PropelException
{
    /**
     * @param string $foreignKeyName
     */
    public function __construct(string $foreignKeyName)
    {
        $message = sprintf('No foreign key %s', $foreignKeyName);

        parent::__construct($message);
    }
}
