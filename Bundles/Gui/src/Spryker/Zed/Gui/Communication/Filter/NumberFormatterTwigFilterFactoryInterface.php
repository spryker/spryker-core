<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Filter;

use Twig\TwigFilter;

interface NumberFormatterTwigFilterFactoryInterface
{
    /**
     * @return \Twig\TwigFilter
     */
    public function createFormatIntFilter(): TwigFilter;

    /**
     * @return \Twig\TwigFilter
     */
    public function createFormatFloatFilter(): TwigFilter;
}
