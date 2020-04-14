<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundle;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;

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

    /**
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function createConfigurableBundleTemplateTransfer(): ConfigurableBundleTemplateTransfer
    {
        $configurableBundleTemplateTranslationTransfers = $this->createTemplateTranslationTransfersForAvailableLocales();

        return (new ConfigurableBundleTemplateTransfer())->setTranslations($configurableBundleTemplateTranslationTransfers);
    }

    /**
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    public function createConfigurableBundleTemplateSlotTransfer(): ConfigurableBundleTemplateSlotTransfer
    {
        $configurableBundleTemplateSlotTranslationTransfers = $this->createSlotTranslationTransfersForAvailableLocales();

        return (new ConfigurableBundleTemplateSlotTransfer())->setTranslations($configurableBundleTemplateSlotTranslationTransfers);
    }

    /**
     * @param array $data
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer[]
     */
    public function createTemplateTranslationTransfersForAvailableLocales(array $data = []): ArrayObject
    {
        $configurableBundleTemplateTranslationTransfers = new ArrayObject();
        $configurableBundleTemplateTranslations = $this->createTemplateTranslationsForAvailableLocales($data);

        foreach ($configurableBundleTemplateTranslations as $configurableBundleTemplateTranslation) {
            $configurableBundleTemplateTranslationTransfers->append(
                (new ConfigurableBundleTemplateTranslationTransfer())->fromArray($configurableBundleTemplateTranslation)
            );
        }

        return $configurableBundleTemplateTranslationTransfers;
    }

    /**
     * @param array $data
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer[]
     */
    public function createSlotTranslationTransfersForAvailableLocales(array $data = []): ArrayObject
    {
        $configurableBundleTemplateSlotTranslationTransfers = new ArrayObject();
        $configurableBundleTemplateSlotTranslations = $this->createSlotTranslationsForAvailableLocales($data);

        foreach ($configurableBundleTemplateSlotTranslations as $configurableBundleTemplateSlotTranslation) {
            $configurableBundleTemplateSlotTranslationTransfers->append(
                (new ConfigurableBundleTemplateSlotTranslationTransfer())->fromArray($configurableBundleTemplateSlotTranslation)
            );
        }

        return $configurableBundleTemplateSlotTranslationTransfers;
    }

    /**
     * @param int $sortOrder
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function createProductImageTransferWithSortOrder(int $sortOrder): ProductImageTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer */
        $productImageTransfer = (new ProductImageBuilder())
            ->seed([ProductImageTransfer::SORT_ORDER => $sortOrder])
            ->build();

        return $productImageTransfer;
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }
}
