<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Header\AuthToken;

interface AuthTokenInterface
{
    /**
     * @return string
     */
    public function getAuthToken(): string;
}
