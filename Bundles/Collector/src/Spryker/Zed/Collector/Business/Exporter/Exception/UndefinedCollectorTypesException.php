<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Exception;

use RuntimeException;

class UndefinedCollectorTypesException extends RuntimeException
{
    /**
     * @var string
     */
    protected $message = 'Undefined collector types';
}
