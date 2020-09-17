<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Dependency\Service;

interface TwigToUtilTextServiceInterface
{
    /**
     * @param string $string
     * @param string $separator
     *
     * @return string
     */
    public function camelCaseToDash($string, $separator = '-');

    /**
     * @param string $string
     * @param string $separator
     * @param bool $upperCaseFirst
     *
     * @return string
     */
    public function dashToCamelCase($string, $separator = '-', $upperCaseFirst = false);
}
