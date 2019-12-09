<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Form\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface;
use Spryker\Zed\Form\Communication\FormCommunicationFactory;
use Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin;
use Spryker\Zed\Form\FormConfig;
use Spryker\Zed\Form\FormDependencyProvider;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Zed\EventDispatcher\Helper\EventDispatcherHelperTrait;
use SprykerTest\Zed\Testify\Helper\ApplicationHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use SprykerTest\Zed\Testify\Helper\Communication\CommunicationHelperTrait;
use SprykerTest\Zed\Testify\Helper\Communication\DependencyProviderHelperTrait;

class FormHelper extends Module
{
    use ApplicationHelperTrait;
    use CommunicationHelperTrait;
    use BusinessHelperTrait;
    use ConfigHelperTrait;
    use DependencyProviderHelperTrait;
    use EventDispatcherHelperTrait;

    protected const MODULE_NAME = 'Form';
    protected const CONFIG_KEY_FORM_PLUGINS = 'formPlugins';

    /**
     * @var \Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface[]
     */
    protected $formPlugins = [];

    /**
     * @var array
     */
    protected $config = [
        self::CONFIG_KEY_FORM_PLUGINS => [],
    ];

    /**
     * @return void
     */
    public function _initialize(): void
    {
        foreach ($this->config[static::CONFIG_KEY_FORM_PLUGINS] as $formPlugin) {
            $this->formPlugins[$formPlugin] = new $formPlugin();
        }
    }

    /**
     * @return void
     */
    protected function addDependencies(): void
    {
        $this->getDependencyProviderHelper()->setDependency(FormDependencyProvider::PLUGINS_FORM, $this->formPlugins);
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        $this->addDependencies();

        $this->getApplicationHelper()->addApplicationPlugin(
            $this->getFormApplicationPluginStub()
        );
    }

    /**
     * @return \Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin
     */
    protected function getFormApplicationPluginStub()
    {
        /** @var \Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin $formApplicationPlugin */
        $formApplicationPlugin = Stub::make(FormApplicationPlugin::class, [
            'getFactory' => function () {
                return $this->getFactory();
            },
            'getConfig' => function () {
                return $this->getConfig();
            },
        ]);

        return $formApplicationPlugin;
    }

    /**
     * @return \Spryker\Zed\Form\Communication\FormCommunicationFactory
     */
    protected function getFactory(): FormCommunicationFactory
    {
        /** @var \Spryker\Zed\Form\Communication\FormCommunicationFactory $formCommunicationFactory */
        $formCommunicationFactory = $this->getCommunicationHelper()->getFactory(static::MODULE_NAME);

        return $formCommunicationFactory;
    }

    /**
     * @return \Spryker\Zed\Form\FormConfig
     */
    protected function getConfig(): FormConfig
    {
        /** @var \Spryker\Zed\Form\FormConfig $formConfig */
        $formConfig = $this->getConfigHelper()->getModuleConfig(static::MODULE_NAME);

        return $formConfig;
    }

    /**
     * @param \Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface $formPlugin
     *
     * @return $this
     */
    public function addFormPlugin(FormPluginInterface $formPlugin)
    {
        $this->formPlugins[] = $formPlugin;

        $this->addDependencies();

        return $this;
    }
}
