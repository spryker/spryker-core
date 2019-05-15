<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter;

interface ShortCodeConverterInterface
{
    /**
     * @param string $html
     *
     * @return string
     */
    public function replaceShortCode(string $html): string;
}
