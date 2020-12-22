<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Dependency\Service;

interface CustomerUserConnectorGuiToUtilSanitizeInterface
{
    /**
     * @param string|array|object $text
     * @param bool $double
     * @param string|null $charset
     *
     * @return string|array
     */
    public function escapeHtml($text, $double = true, $charset = null);
}
