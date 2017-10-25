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
     *
     * @return string
     */
    public function camelCaseToDash($string);

    /**
     * @param string $string
     *
     * @return string
     */
    public function dashToCamelCase($string);
}
