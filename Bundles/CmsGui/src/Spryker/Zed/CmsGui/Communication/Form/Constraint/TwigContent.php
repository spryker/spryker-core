<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class TwigContent extends SymfonyConstraint
{
    /**
     * @var string
     */
    public const OPTION_TWIG_ENVIRONMENT = 'twigEnvironment';

    /**
     * @var \Twig\Environment
     */
    protected $twigEnvironment;

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment()
    {
        return $this->twigEnvironment;
    }
}
