<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Exception;

use Exception;

class RowActionNotFoundException extends Exception
{
    /**
     * @var string
     */
    protected const MESSAGE_TEMPLATE = 'Failed to find row action by id: %s.';

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        parent::__construct(sprintf(static::MESSAGE_TEMPLATE, $id));
    }
}
