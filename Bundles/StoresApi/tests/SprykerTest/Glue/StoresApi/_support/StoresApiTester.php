<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\StoresApi;

use Codeception\Actor;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\StoresApi\Plugin\GlueApplication\StoreValidatorPlugin;
use Spryker\Glue\StoresApi\Plugin\GlueBackendApiApplication\StoreApplicationPlugin as BackendStoreApplicationPlugin;
use Spryker\Glue\StoresApi\Plugin\GlueStorefrontApiApplication\StoreApplicationPlugin;
use Spryker\Glue\StoresApi\Plugin\GlueStorefrontApiApplication\StoresResource;
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
 * @SuppressWarnings(\SprykerTest\Glue\StoresApi\PHPMD)
 */
class StoresApiTester extends Actor
{
    use _generated\StoresApiTesterActions;

    /**
     * @return \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface
     */
    public function createStoresResource(): JsonApiResourceInterface
    {
        return new StoresResource();
    }

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

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface
     */
    public function createBackendStoreApplicationPlugin(): ApplicationPluginInterface
    {
        return new BackendStoreApplicationPlugin();
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface
     */
    public function createStoreValidatorPlugin(): RequestValidatorPluginInterface
    {
        return new StoreValidatorPlugin();
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function createGlueRequestTransfer(): GlueRequestTransfer
    {
        return new GlueRequestTransfer();
    }
}
