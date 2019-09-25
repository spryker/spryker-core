<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Service\UtilSanitize;

interface UtilSanitizeServiceInterface
{
    /**
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
     *  - Filters null elements of an array recursively.
     *
     * @api
     *
     * @param array $array
     *
     * @return array
     */
    public function arrayFilterRecursive(array $array);

    /**
     * Specification:
     *  - Filters out elements of an array recursively.
     *  - Filters out all null values, empty strings, empty arrays and countables without elements.
     *  - Does not filters out boolean, numeric values and any other not blank values.
     *
     * @api
     *
     * @param array $array
     *
     * @return array
     */
    public function filterOutBlankValuesRecursively(array $array): array;
}
