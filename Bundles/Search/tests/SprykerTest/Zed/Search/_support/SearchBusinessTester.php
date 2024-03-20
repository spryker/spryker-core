<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search;

use Codeception\Actor;
use Codeception\Stub;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Search\Business\Model\SearchInstallerInterface;
use Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface;
use Spryker\Zed\SearchExtension\Dependency\Plugin\StoreAwareInstallPluginInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\Search\Business\SearchBusinessFactory getFactory()
 * @method \Spryker\Zed\Search\Business\SearchFacade getFacade() ()
 *
 * @SuppressWarnings(\SprykerTest\Zed\Search\PHPMD)
 */
class SearchBusinessTester extends Actor
{
    use _generated\SearchBusinessTesterActions;

    /**
     * @var string
     */
    public const STORE = 'DE';

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function createLogger(): LoggerInterface
    {
        return Stub::makeEmpty(LoggerInterface::class);
    }

    /**
     * @return \Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface
     */
    public function createInstallPlugin(): InstallPluginInterface
    {
        return Stub::makeEmpty(InstallPluginInterface::class);
    }

    /**
     * @return \Spryker\Zed\SearchExtension\Dependency\Plugin\StoreAwareInstallPluginInterface
     */
    public function createStoreAwareInstallPlugin(): StoreAwareInstallPluginInterface
    {
        return Stub::makeEmpty(StoreAwareInstallPluginInterface::class);
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\SearchInstallerInterface
     */
    public function createSearchInstaller(): SearchInstallerInterface
    {
        return Stub::makeEmpty(SearchInstallerInterface::class);
    }
}
