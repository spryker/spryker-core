<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Service\UtilSanitize;

interface UtilSanitizeServiceInterface
{
    /**
     *
     * Specification:
     *  - Escapes any string for safe output in HTML.
     *
     * @api
     *
     * @param string $text
     * @param bool $double
     * @param string|null $charset
     *
     * @return string
     */
    public function escapeHtml($text, $double = true, $charset = null);

    /**
     * Specification:
     *  - Filters null elements of an array recursively
     *
     * @api
     *
     * @param array $array
     *
     * @return array
     */
    public function arrayFilterRecursive(array $array);
}
