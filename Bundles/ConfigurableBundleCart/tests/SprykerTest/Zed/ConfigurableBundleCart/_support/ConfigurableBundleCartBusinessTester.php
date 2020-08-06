<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleCart;

use Codeception\Actor;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;

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
 * @method \Spryker\Zed\ConfigurableBundleCart\Business\ConfigurableBundleCartFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ConfigurableBundleCartBusinessTester extends Actor
{
    use _generated\ConfigurableBundleCartBusinessTesterActions;

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
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    public function createConfigurableBundleTemplateSlot(array $data = []): ConfigurableBundleTemplateSlotTransfer
    {
        $defaultData = [
            ConfigurableBundleTemplateSlotTransfer::NAME => 'configurable_bundle.template_slots.test-name',
            ConfigurableBundleTemplateSlotTransfer::UUID => uniqid(),
            ConfigurableBundleTemplateSlotTransfer::TRANSLATIONS => $this->createSlotTranslationsForAvailableLocales(),
        ];

        return $this->haveConfigurableBundleTemplateSlot(array_merge($data, $defaultData));
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
     * @param array $data
     *
     * @return array
     */
    public function createSlotTranslationsForAvailableLocales(array $data = []): array
    {
        $availableLocaleTransfers = $this->getLocator()
            ->locale()
            ->facade()
            ->getLocaleCollection();

        $configurableBundleTemplateSlotTranslationTransfers = [];

        foreach ($availableLocaleTransfers as $localeTransfer) {
            $defaultData = [
                ConfigurableBundleTemplateSlotTranslationTransfer::NAME => 'test-name',
                ConfigurableBundleTemplateSlotTranslationTransfer::LOCALE => $localeTransfer,
            ];

            $configurableBundleTemplateSlotTranslationTransfers[] = array_merge($defaultData, $data);
        }

        return $configurableBundleTemplateSlotTranslationTransfers;
    }
}
