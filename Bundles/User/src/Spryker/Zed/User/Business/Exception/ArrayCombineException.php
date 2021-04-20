<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business\Exception;

use Exception;

class ArrayCombineException extends Exception
{
    public function __construct()
    {
        parent::__construct($this->buildMessage());
    }

    /**
     * @return string
     */
    protected function buildMessage(): string
    {
        return 'Failed to combine arrays with different number of elements.';
    }
}
