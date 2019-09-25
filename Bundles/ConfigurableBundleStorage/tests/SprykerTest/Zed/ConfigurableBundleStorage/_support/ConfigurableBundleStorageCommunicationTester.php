<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleStorage;

use Codeception\Actor;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate;
use Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage;
use Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorageQuery;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ConfigurableBundleStorageCommunicationTester extends Actor
{
    use _generated\ConfigurableBundleStorageCommunicationTesterActions;

    /**
     * Define custom actions here
     */
    protected const TEST_CONFIGURABLE_BUNDLE_NAME = 'configurable-bundle-1';

    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate
     */
    public function createConfigurableBundleTemplate(): SpyConfigurableBundleTemplate
    {
        $configurableBundleTemplateEntity = (new SpyConfigurableBundleTemplate())
            ->setName(static::TEST_CONFIGURABLE_BUNDLE_NAME);

        $configurableBundleTemplateEntity->save();

        return $configurableBundleTemplateEntity;
    }

    /**
     * @param int $idConfigurableBundleTemplateStorage
     *
     * @return \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage|null
     */
    public function findConfigurableBundleTemplateStorageById(int $idConfigurableBundleTemplateStorage): ?SpyConfigurableBundleTemplateStorage
    {
        return SpyConfigurableBundleTemplateStorageQuery::create()
            ->filterByFkConfigurableBundleTemplate($idConfigurableBundleTemplateStorage)
            ->findOne();
    }
}
