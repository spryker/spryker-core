<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Http;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class HttpConfig extends AbstractSharedConfig
{
    /**
     * @return string
     */
    public function getUriSignerSecret(): string
    {
        return md5(__DIR__);
    }
}
