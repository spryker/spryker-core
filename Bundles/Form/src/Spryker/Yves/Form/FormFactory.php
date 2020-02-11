<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Form;

use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\Form\FormFactoryBuilder;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Security\Csrf\TokenStorage\ClearableTokenStorageInterface;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;

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
     * @return \Symfony\Component\Security\Csrf\TokenStorage\ClearableTokenStorageInterface
     */
    public function createDefaultTokenStorage(): ClearableTokenStorageInterface
    {
        return new NativeSessionTokenStorage();
    }

    /**
     * @return \Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface[]
     */
    public function getFormPlugins(): array
    {
        return $this->getProvidedDependency(FormDependencyProvider::PLUGINS_FORM);
    }
}
