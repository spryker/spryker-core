<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Propel\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Propel\Runtime\Propel;
use ReflectionMethod;
use Silex\Application;
use Spryker\Service\Container\Container;
use Spryker\Zed\Propel\Communication\Plugin\Application\PropelApplicationPlugin;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
use Throwable;

class TransactionHelper extends Module
{
    /**
     * @return void
     */
    public function _initialize(): void
    {
        Propel::disableInstancePooling();

        if (class_exists(PropelApplicationPlugin::class)) {
            $propelApplicationPlugin = new PropelApplicationPlugin();
            $propelApplicationPlugin->provide(new Container());

            return;
        }

        $this->addBackwardCompatibleServiceProvider();
    }

    /**
     * @deprecated Will be removed in favor of {@link \Spryker\Zed\Propel\Communication\Plugin\Application\PropelApplicationPlugin}.
     *
     * @return void
     */
    protected function addBackwardCompatibleServiceProvider(): void
    {
        $propelServiceProvider = new PropelServiceProvider();
        $propelServiceProvider->boot(new Application());
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        try {
            $reflectionMethod = new ReflectionMethod($test, $test->getName());
            $docBlock = $reflectionMethod->getDocComment();

            if (strpos($docBlock, '@disableTransaction') !== false) {
                return;
            }
        } catch (Throwable $throwable) {
        }

        Propel::getWriteConnection('zed')->beginTransaction();
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        parent::_after($test);

        Propel::getWriteConnection('zed')->forceRollBack();
    }

    /**
     * @return void
     */
    public function _afterSuite(): void
    {
        Propel::closeConnections();
    }
}
