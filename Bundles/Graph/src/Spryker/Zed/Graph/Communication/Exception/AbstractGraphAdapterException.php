<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Graph\Communication\Exception;

use Exception;

abstract class AbstractGraphAdapterException extends Exception
{
    public const MESSAGE = 'Please check the return value of your GraphConfig::getGraphAdapterName(). This should be something like "GraphAdapter::class"';
}
