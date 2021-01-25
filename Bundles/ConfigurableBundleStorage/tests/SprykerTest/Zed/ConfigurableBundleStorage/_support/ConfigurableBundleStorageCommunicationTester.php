<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorage;
use Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorageQuery;
use Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage;
use Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorageQuery;

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
 *
 * @SuppressWarnings(PHPMD)
 */
class ConfigurableBundleStorageCommunicationTester extends Actor
{
    use _generated\ConfigurableBundleStorageCommunicationTesterActions;

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function createActiveConfigurableBundleTemplate(array $data = []): ConfigurableBundleTemplateTransfer
    {
        $defaultData = [
            ConfigurableBundleTemplateTransfer::NAME => 'configurable_bundle.templates.test-name',
            ConfigurableBundleTemplateTransfer::UUID => uniqid(),
            ConfigurableBundleTemplateTransfer::IS_ACTIVE => true,
            ConfigurableBundleTemplateTransfer::TRANSLATIONS => $this->createTemplateTranslationsForAvailableLocales(),
        ];

        return $this->haveConfigurableBundleTemplate(array_merge($data, $defaultData));
    }

    /**
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function createDeactivatedConfigurableBundleTemplate(): ConfigurableBundleTemplateTransfer
    {
        return $this->haveConfigurableBundleTemplate([
            ConfigurableBundleTemplateTransfer::NAME => 'template.test-name',
            ConfigurableBundleTemplateTransfer::IS_ACTIVE => false,
            ConfigurableBundleTemplateTransfer::UUID => uniqid(),
            ConfigurableBundleTemplateTransfer::TRANSLATIONS => $this->createTemplateTranslationsForAvailableLocales(),
        ]);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function createTemplateTranslationsForAvailableLocales(array $data = []): array
    {
        $availableLocaleTransfers = $this->getLocator()
            ->locale()
            ->facade()
            ->getLocaleCollection();

        $configurableBundleTemplateTranslationTransfers = [];

        foreach ($availableLocaleTransfers as $localeTransfer) {
            $defaultData = [
                ConfigurableBundleTemplateTranslationTransfer::NAME => 'test-name',
                ConfigurableBundleTemplateTranslationTransfer::LOCALE => $localeTransfer,
            ];

            $configurableBundleTemplateTranslationTransfers[] = array_merge($defaultData, $data);
        }

        return $configurableBundleTemplateTranslationTransfers;
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

    /**
     * @param int $idConfigurableBundleTemplateStorage
     *
     * @return \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorage|null
     */
    public function findConfigurableBundleTemplateImageStorageById(int $idConfigurableBundleTemplateStorage): ?SpyConfigurableBundleTemplateImageStorage
    {
        return SpyConfigurableBundleTemplateImageStorageQuery::create()
            ->filterByFkConfigurableBundleTemplate($idConfigurableBundleTemplateStorage)
            ->findOne();
    }
}
