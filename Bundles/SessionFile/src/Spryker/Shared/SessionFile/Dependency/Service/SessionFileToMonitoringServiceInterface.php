<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionFile\Dependency\Service;

interface SessionFileToMonitoringServiceInterface
{
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addCustomParameter(string $key, $value): void;
}
