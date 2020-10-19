<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleGui;

use Codeception\Actor;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;

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
class ConfigurableBundleGuiCommunicationTester extends Actor
{
    use _generated\ConfigurableBundleGuiCommunicationTesterActions;

    protected const TRANSLATION_TRANSFER_NAME = 'test-name';

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function createConfigurableBundleTemplate(string $name): ConfigurableBundleTemplateTransfer
    {
        return $this->haveConfigurableBundleTemplate([
            ConfigurableBundleTemplateTransfer::NAME => $name,
            ConfigurableBundleTemplateTransfer::UUID => uniqid(),
            ConfigurableBundleTemplateTransfer::IS_ACTIVE => true,
            ConfigurableBundleTemplateTransfer::TRANSLATIONS => $this->getTemplateTranslations(),
        ]);
    }

    /**
     * @return array
     */
    protected function getTemplateTranslations(): array
    {
        $availableLocaleTransfers = $this->getLocator()
            ->locale()
            ->facade()
            ->getLocaleCollection();

        $templateTranslations = [];

        foreach ($availableLocaleTransfers as $availableLocaleTransfer) {
            $templateTranslations[] = [
                ConfigurableBundleTemplateTranslationTransfer::NAME => static::TRANSLATION_TRANSFER_NAME,
                ConfigurableBundleTemplateTranslationTransfer::LOCALE => $availableLocaleTransfer,
            ];
        }

        return $templateTranslations;
    }
}
