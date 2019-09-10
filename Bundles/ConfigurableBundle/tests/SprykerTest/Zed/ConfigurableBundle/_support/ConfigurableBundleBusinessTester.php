<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundle;

use Codeception\Actor;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use ArrayObject;

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
 * @method \Spryker\Zed\ConfigurableBundle\Business\ConfigurableBundleFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ConfigurableBundleBusinessTester extends Actor
{
    use _generated\ConfigurableBundleBusinessTesterActions;

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function createActiveConfigurableBundleTemplate(array $data = []): ConfigurableBundleTemplateTransfer
    {
        $defaultData = [
            ConfigurableBundleTemplateTransfer::NAME => 'configurable_bundle.template.test-name',
            ConfigurableBundleTemplateTransfer::UUID => uniqid(),
            ConfigurableBundleTemplateTransfer::IS_ACTIVE => true,
            ConfigurableBundleTemplateTransfer::TRANSLATIONS => $this->createTranslationsForAvailableLocales(),
        ];

        return $this->haveConfigurableBundleTemplate(array_merge($data, $defaultData));
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function createTranslationsForAvailableLocales(array $data = []): array
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
     * @return \ArrayObject|\Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer[]
     */
    public function createTranslationTransfersForAvailableLocales(array $data = []): ArrayObject
    {
        $configurableBundleTemplateTranslationTransfers = new ArrayObject();
        $configurableBundleTemplateTranslations = $this->createTranslationsForAvailableLocales($data);

        foreach ($configurableBundleTemplateTranslations as $configurableBundleTemplateTranslation) {
            $configurableBundleTemplateTranslationTransfers->append(
                (new ConfigurableBundleTemplateTranslationTransfer())->fromArray($configurableBundleTemplateTranslation)
            );
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
