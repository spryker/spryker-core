<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Dependency\Service;

interface SecurityOauthUserToUtilTextServiceInterface
{
    /**
     * @param int $length
     *
     * @return string
     */
    public function generateRandomByteString(int $length = 32): string;
}
