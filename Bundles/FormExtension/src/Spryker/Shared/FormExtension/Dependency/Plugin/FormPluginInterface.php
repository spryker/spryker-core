<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\FormExtension\Dependency\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\Form\FormFactoryBuilderInterface;

interface FormPluginInterface
{
    /**
     * Specification:
     * - Extends form factory builder.
     * - Can be used for adding form extensions, for types or form type extensions.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormFactoryBuilderInterface $formFactoryBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Form\FormFactoryBuilderInterface
     */
    public function extend(FormFactoryBuilderInterface $formFactoryBuilder, ContainerInterface $container): FormFactoryBuilderInterface;
}
