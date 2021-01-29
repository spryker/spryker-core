<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

abstract class TwigExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @return array
     */
    public function getGlobals(): array
    {
        return [];
    }
}
