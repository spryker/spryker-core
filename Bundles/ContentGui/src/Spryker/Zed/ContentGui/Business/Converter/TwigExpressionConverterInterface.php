<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter;

interface TwigExpressionConverterInterface
{
    /**
     * @param string $html
     *
     * @return string
     */
    public function replaceTwigExpression(string $html): string;
}
