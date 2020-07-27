<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Twig\Helper;

trait TwigHelperTrait
{
    /**
     * @return \SprykerTest\Zed\Twig\Helper\TwigHelper
     */
    protected function getTwigHelper(): TwigHelper
    {
        /** @var \SprykerTest\Zed\Twig\Helper\TwigHelper $twigHelper */
        $twigHelper = $this->getModule('\\' . TwigHelper::class);

        return $twigHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
