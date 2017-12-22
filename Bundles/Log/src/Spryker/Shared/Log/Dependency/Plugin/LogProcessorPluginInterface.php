<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Dependency\Plugin;

interface LogProcessorPluginInterface
{
    /**
     * Specification:
     * - Processes log data and returns data after processing.
     *
     * @api
     *
     * @param array $data
     *
     * @return array
     */
    public function __invoke(array $data);
}
