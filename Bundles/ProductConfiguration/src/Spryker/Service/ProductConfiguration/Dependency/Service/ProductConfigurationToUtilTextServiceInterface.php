<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfiguration\Dependency\Service;

interface ProductConfigurationToUtilTextServiceInterface
{
    /**
     * @param mixed $value
     * @param string $algorithm
     *
     * @return string
     */
    public function hashValue($value, string $algorithm): string;

    /**
     * @param string $string
     * @param string $separator
     *
     * @return string
     */
    public function camelCaseToSeparator(string $string, string $separator = '-'): string;
}
