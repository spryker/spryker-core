<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitizeXss;

interface UtilSanitizeXssServiceInterface
{
    /**
     * Specification:
     *  - Sanitizes untrusted HTML to prevent XSS attacks.
     *
     * @api
     *
     * @param string $text
     * @param string[] $allowedAttributes
     * @param string[] $allowedHtmlTags
     *
     * @return string
     */
    public function sanitizeXss(string $text, array $allowedAttributes = [], array $allowedHtmlTags = []): string;
}
