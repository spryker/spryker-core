<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundle;

use Codeception\Actor;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 *
 * @SuppressWarnings(PHPMD)
 */
class ConfigurableBundleBusinessTester extends Actor
{
    use _generated\ConfigurableBundleBusinessTesterActions;

    /**
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function createActiveConfigurableBundleTemplate(): ConfigurableBundleTemplateTransfer
    {
        return $this->haveConfigurableBundleTemplate([
            ConfigurableBundleTemplateTransfer::NAME => 'template.test-name',
            ConfigurableBundleTemplateTransfer::UUID => uniqid(),
            ConfigurableBundleTemplateTransfer::IS_ACTIVE => true,
            ConfigurableBundleTemplateTransfer::TRANSLATIONS => $this->createTranslationsForAvailableLocales(),
        ]);
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
            ConfigurableBundleTemplateTransfer::TRANSLATIONS => $this->createTranslationsForAvailableLocales(),
        ]);
    }

    /**
     * @param string $templateUuid
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function createItemTransferWithConfigurableBundleTemplateUuid(string $templateUuid): ItemTransfer
    {
        return (new ItemTransfer())
            ->setConfiguredBundle(
                (new ConfiguredBundleTransfer())
                    ->setTemplate(
                        (new ConfigurableBundleTemplateTransfer())
                            ->setUuid($templateUuid)
                    )
            );
    }

    /**
     * @return array
     */
    protected function createTranslationsForAvailableLocales(): array
    {
        $availableLocaleTransfers = $this->getLocator()
            ->locale()
            ->facade()
            ->getLocaleCollection();

        $configurableBundleTemplateTranslationTransfers = [];

        foreach ($availableLocaleTransfers as $localeTransfer) {
            $configurableBundleTemplateTranslationTransfers[] = [
                ConfigurableBundleTemplateTranslationTransfer::NAME => 'test-name',
                ConfigurableBundleTemplateTranslationTransfer::LOCALE => $localeTransfer,
            ];
        }

        return $configurableBundleTemplateTranslationTransfers;
    }

    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithConfigurableBundleTemplate(string $uuid): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->addItem(
                $this->createItemTransferWithConfigurableBundleTemplateUuid($uuid)
            );
    }
}
