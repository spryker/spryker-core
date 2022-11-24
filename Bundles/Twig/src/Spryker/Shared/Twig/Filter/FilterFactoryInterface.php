<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Filter;

use Twig\Environment;
use Twig\TwigFilter;

interface FilterFactoryInterface
{
    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\TwigFilter
     */
    public function createExecuteFilterIfExistsFilter(Environment $twig): TwigFilter;
}
