<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Business\Reader;

use Spryker\Shared\ZedRequest\Client\AbstractRequest;

interface ZedRequestReaderInterface
{
    /**
     * @return \Spryker\Shared\ZedRequest\Client\AbstractRequest
     */
    public function getCurrentZedRequest(): AbstractRequest;
}
