<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitizeExtension\Dependency\Plugin;

interface StringSanitizerPluginInterface
{
    /**
     * Specification:
     * - Sanitizes a given string value with the given replacement.
     *
     * @api
     *
     * @param string $value
     * @param string $replacement
     *
     * @return string
     */
    public function sanitize(string $value, string $replacement): string;
}
