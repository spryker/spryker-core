<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Form\Communication;

use Spryker\Zed\Form\FormDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormFactoryBuilder;
use Symfony\Component\Form\FormFactoryBuilderInterface;

/**
 * @method \Spryker\Zed\Form\FormConfig getConfig()
 */
class FormCommunicationFactory extends AbstractCommunicationFactory
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
    public function getFormPlugins(): array
    {
        return $this->getProvidedDependency(FormDependencyProvider::PLUGINS_FORM);
    }
}
