<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Config\Business;

interface ConfigFacadeInterface
{
    /**
     * Specification:
     * - Returns a list of key/value pairs for used configurations.
     *
     * @api
     *
     * @return array
     */
    public function getProfileData(): array;
}
