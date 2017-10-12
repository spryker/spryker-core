<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncoding;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilEncoding\Model\Json;

class UtilEncodingServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilEncoding\Model\JsonInterface
     */
    public function createJsonEncoder()
    {
        return new Json();
    }
}
