<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig;

abstract class TwigFunctionProvider
{
    /**
     * @return string
     */
    abstract public function getFunctionName();

    /**
     * @return callable
     */
    abstract public function getFunction();

    /**
     * @return array<string, mixed>
     */
    public function getOptions()
    {
        return ['is_safe' => ['html']];
    }
}
