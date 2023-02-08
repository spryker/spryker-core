<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Dependency\Service;

interface WarehouseUserGuiToUtilSanitizeServiceInterface
{
    /**
     * @param string $text
     * @param bool $double
     * @param string|null $charset
     *
     * @return string
     */
    public function escapeHtml(string $text, bool $double = true, ?string $charset = null): string;
}
