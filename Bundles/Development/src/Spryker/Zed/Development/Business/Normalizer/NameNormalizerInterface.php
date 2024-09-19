<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Normalizer;

interface NameNormalizerInterface
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function dasherize(string $name): string;

    /**
     * @param string $name
     *
     * @return string
     */
    public function camelize(string $name): string;
}
