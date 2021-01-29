<?php
// phpcs:ignoreFile

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig;

use Twig\Environment;
use Twig\TwigFilter as BaseTwigFilter;

if (Environment::MAJOR_VERSION < 3) {
    /**
     * @deprecated This class exists for BC reason. Please adjust your Twig filter in order to not use any base class for it.
     */
    class TwigFilter extends BaseTwigFilter
    {
    }
}

