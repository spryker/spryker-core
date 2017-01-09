<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Library\Twig;

use Twig_SimpleFilter;

/**
 * @deprecated Use Twig bundle instead.
 */
abstract class TwigFilter extends Twig_SimpleFilter
{

    public function __construct()
    {
        parent::__construct($this->getFilterName(), $this->getFunction(), $this->getOptions());
    }

    /**
     * @return string
     */
    abstract protected function getFilterName();

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
