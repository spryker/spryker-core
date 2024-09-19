<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Normalizer;

use Laminas\Filter\FilterChain;
use Laminas\Filter\StringToLower;
use Laminas\Filter\Word\CamelCaseToDash;
use Laminas\Filter\Word\UnderscoreToCamelCase;

class NameNormalizer implements NameNormalizerInterface
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function dasherize(string $name): string
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $filterChain->filter($name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function camelize(string $name): string
    {
        /** @var string $name */
        $name = str_replace('-', '_', $name);
        $filter = new UnderscoreToCamelCase();

        /** @var string $camelCasedValue */
        $camelCasedValue = $filter->filter($name);

        return ucfirst($camelCasedValue);
    }
}
