<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Dependency;

interface CustomerDataChangeRequestToUtilTextServiceInterface
{
    /**
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString(int $length): string;
}
