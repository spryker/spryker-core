<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilEncoding\Business;

use Spryker\Shared\UtilEncoding\Json;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\UtilEncoding\UtilEncodingConfig getConfig()
 */
class UtilEncodingBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Shared\UtilEncoding\JsonInterface
     */
    public function createJsonEncoder()
    {
        return new Json();
    }

}
