<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\StoresBackendApi;

use Codeception\Actor;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\StoresBackendApi\Plugin\GlueBackendApiApplication\StoreApplicationPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class StoresBackendApiTester extends Actor
{
    use _generated\StoresBackendApiTesterActions;

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function createContainer(): ContainerInterface
    {
        return new Container();
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface
     */
    public function createStoreApplicationPlugin(): ApplicationPluginInterface
    {
        return new StoreApplicationPlugin();
    }
}
