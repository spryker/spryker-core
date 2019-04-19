<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig;

use Twig\TwigFunction as BaseTwigFunction;

abstract class TwigFunction extends BaseTwigFunction
{
    public function __construct()
    {
        parent::__construct($this->getFunctionName(), $this->getFunction(), $this->getOptions());
    }

    /**
     * @return string
     */
    abstract protected function getFunctionName();

    /**
     * @return callable
     */
    abstract protected function getFunction();

    /**
     * @return array
     */
    protected function getOptions()
    {
        return ['is_safe' => ['html']];
    }
}
