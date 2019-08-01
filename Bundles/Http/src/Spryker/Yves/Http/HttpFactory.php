<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Http;

use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\Form\Extension\HttpFoundation\Type\FormTypeHttpFoundationExtension;
use Symfony\Component\Form\FormTypeExtensionInterface;

class HttpFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormTypeExtensionInterface
     */
    public function createFormTypeHttpFoundationExtension(): FormTypeExtensionInterface
    {
        return new FormTypeHttpFoundationExtension();
    }
}
