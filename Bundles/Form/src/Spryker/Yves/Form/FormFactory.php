<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Form;

use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\Form\FormFactoryBuilder;
use Symfony\Component\Form\FormFactoryBuilderInterface;

/**
 * @method \Spryker\Yves\Form\FormConfig getConfig()
 */
class FormFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormFactoryBuilderInterface
     */
    public function createFormFactoryBuilder(): FormFactoryBuilderInterface
    {
        return new FormFactoryBuilder();
    }

    /**
     * @return \Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface[]
     */
    public function getFormExtensionPlugins(): array
    {
        return $this->getProvidedDependency(FormDependencyProvider::PLUGINS_FORM_EXTENSION);
    }
}
