<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Dashboard\Dependency\Plugin;

interface DashboardPluginInterface
{
    /**
     * Specification:
     * - Returns rendered content.
     *
     * @api
     *
     * @return string
     */
    public function render(): string;
}
