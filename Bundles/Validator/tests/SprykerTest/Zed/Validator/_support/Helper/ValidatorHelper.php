<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Validator\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Spryker\Shared\ValidatorExtension\Dependency\Plugin\ConstraintPluginInterface;
use Spryker\Shared\ValidatorExtension\Dependency\Plugin\ValidatorPluginInterface;
use Spryker\Zed\Validator\Communication\Plugin\Application\ValidatorApplicationPlugin;
use Spryker\Zed\Validator\Communication\ValidatorCommunicationFactory;
use Spryker\Zed\Validator\ValidatorDependencyProvider;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Zed\Application\Helper\ApplicationHelperTrait;
use SprykerTest\Zed\EventDispatcher\Helper\EventDispatcherHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use SprykerTest\Zed\Testify\Helper\Communication\CommunicationHelperTrait;
use SprykerTest\Zed\Testify\Helper\Communication\DependencyProviderHelperTrait;

class ValidatorHelper extends Module
{
    use ApplicationHelperTrait;
    use CommunicationHelperTrait;
    use BusinessHelperTrait;
    use ConfigHelperTrait;
    use DependencyProviderHelperTrait;
    use EventDispatcherHelperTrait;

    protected const MODULE_NAME = 'Validator';
    protected const CONFIG_KEY_VALIDATOR_PLUGINS = 'validatorPlugins';
    protected const CONFIG_KEY_CONSTRAINT_PLUGINS = 'constraintPlugins';

    /**
     * @var \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ValidatorPluginInterface[]
     */
    protected $validatorPlugins = [];

    /**
     * @var \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ConstraintPluginInterface[]
     */
    protected $constraintPlugins = [];

    /**
     * @var array
     */
    protected $config = [
        self::CONFIG_KEY_VALIDATOR_PLUGINS => [],
        self::CONFIG_KEY_CONSTRAINT_PLUGINS => [],
    ];

    /**
     * @return void
     */
    public function _initialize(): void
    {
        foreach ($this->config[static::CONFIG_KEY_VALIDATOR_PLUGINS] as $constraintPlugin) {
            $this->validatorPlugins[$constraintPlugin] = new $constraintPlugin();
        }
        foreach ($this->config[static::CONFIG_KEY_CONSTRAINT_PLUGINS] as $constraintPlugin) {
            $this->constraintPlugins[$constraintPlugin] = new $constraintPlugin();
        }
    }

    /**
     * @return void
     */
    protected function addDependencies(): void
    {
        $this->getDependencyProviderHelper()->setDependency(ValidatorDependencyProvider::PLUGINS_VALIDATOR, $this->validatorPlugins);
        $this->getDependencyProviderHelper()->setDependency(ValidatorDependencyProvider::PLUGINS_CONSTRAINT, $this->constraintPlugins);
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
            $this->getValidatorApplicationPluginStub()
        );
    }

    /**
     * @return \Spryker\Zed\Validator\Communication\Plugin\Application\ValidatorApplicationPlugin
     */
    protected function getValidatorApplicationPluginStub()
    {
        /** @var \Spryker\Zed\Validator\Communication\Plugin\Application\ValidatorApplicationPlugin $validatorApplicationPlugin */
        $validatorApplicationPlugin = Stub::make(ValidatorApplicationPlugin::class, [
            'getFactory' => function () {
                return $this->getFactory();
            },
        ]);

        return $validatorApplicationPlugin;
    }

    /**
     * @return \Spryker\Zed\Validator\Communication\ValidatorCommunicationFactory
     */
    protected function getFactory(): ValidatorCommunicationFactory
    {
        /** @var \Spryker\Zed\Validator\Communication\ValidatorCommunicationFactory $validatorCommunicationFactory */
        $validatorCommunicationFactory = $this->getCommunicationHelper()->getFactory(static::MODULE_NAME);

        return $validatorCommunicationFactory;
    }

    /**
     * @param \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ValidatorPluginInterface $validatorPlugin
     *
     * @return $this
     */
    public function addValidatorPlugin(ValidatorPluginInterface $validatorPlugin)
    {
        $this->validatorPlugins[] = $validatorPlugin;

        $this->addDependencies();

        return $this;
    }

    /**
     * @param \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ConstraintPluginInterface $constraintPlugin
     *
     * @return $this
     */
    public function addConstraintPlugin(ConstraintPluginInterface $constraintPlugin)
    {
        $this->constraintPlugins[] = $constraintPlugin;

        $this->addDependencies();

        return $this;
    }
}
