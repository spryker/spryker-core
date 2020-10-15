<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleCartsRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ConfigurableBundleTemplateBuilder;
use Generated\Shared\DataBuilder\ConfiguredBundleBuilder;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Business\ConfigurableBundleCartsRestApiFacadeInterface;
use Spryker\Zed\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiDependencyProvider;
use Spryker\Zed\Kernel\Container;

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
 * @method \Spryker\Zed\ConfigurableBundleCartsRestApi\Business\ConfigurableBundleCartsRestApiFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ConfigurableBundleCartsRestApiBusinessTester extends Actor
{
    use _generated\ConfigurableBundleCartsRestApiBusinessTesterActions;

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ConfigurableBundleCartsRestApi\Business\ConfigurableBundleCartsRestApiBusinessFactory $configurableBundleCartsRestApiBusinessFactoryMock
     *
     * @return \Spryker\Zed\ConfigurableBundleCartsRestApi\Business\ConfigurableBundleCartsRestApiFacadeInterface
     */
    public function getFacadeMock(MockObject $configurableBundleCartsRestApiBusinessFactoryMock): ConfigurableBundleCartsRestApiFacadeInterface
    {
        $container = new Container();
        $configurableBundleCartsRestApiDependencyProvider = new ConfigurableBundleCartsRestApiDependencyProvider();
        $configurableBundleCartsRestApiDependencyProvider->provideBusinessLayerDependencies($container);

        $configurableBundleCartsRestApiBusinessFactoryMock->setContainer($container);

        /** @var \Spryker\Zed\ConfigurableBundleCartsRestApi\Business\ConfigurableBundleCartsRestApiFacade $configurableBundleCartsRestApiFacade */
        $configurableBundleCartsRestApiFacade = $this->getFacade();
        $configurableBundleCartsRestApiFacade->setFactory($configurableBundleCartsRestApiBusinessFactoryMock);

        return $configurableBundleCartsRestApiFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer
     */
    public function buildCreateConfiguredBundleRequest(): CreateConfiguredBundleRequestTransfer
    {
        $configurableBundleTemplateTransfer = $this->createActiveConfigurableBundleTemplate();
        $firstConfigurableBundleTemplateSlotTransfer = $this->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);
        $secondConfigurableBundleTemplateSlotTransfer = $this->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        $quoteTransfer = $this->havePersistentQuote(
            [
                QuoteTransfer::UUID => uniqid('uuid', true),
                QuoteTransfer::CUSTOMER => $this->haveCustomer(),
            ]
        );

        $firstProductConcrete = $this->haveProduct();
        $firstItemTransfer = (new ItemTransfer())
            ->setQuantity(1)
            ->setSku($firstProductConcrete->getSku())
            ->setGroupKey($firstProductConcrete->getSku())
            ->setConfiguredBundleItem($this->createConfiguredBundleItem($firstConfigurableBundleTemplateSlotTransfer->getUuid()))
            ->setConfiguredBundle(
                (new ConfiguredBundleTransfer())
                    ->setQuantity(1)
                    ->setTemplate((new ConfigurableBundleTemplateTransfer())->setUuid($configurableBundleTemplateTransfer->getUuid()))
            );

        $secondProductConcrete = $this->haveProduct();
        $secondItemTransfer = (new ItemTransfer())
            ->setQuantity(2)
            ->setSku($secondProductConcrete->getSku())
            ->setGroupKey($secondProductConcrete->getSku())
            ->setConfiguredBundleItem($this->createConfiguredBundleItem($secondConfigurableBundleTemplateSlotTransfer->getUuid()))
            ->setConfiguredBundle(
                (new ConfiguredBundleTransfer())
                    ->setQuantity(1)
                    ->setTemplate((new ConfigurableBundleTemplateTransfer())->setUuid($configurableBundleTemplateTransfer->getUuid()))
            );

        return $createConfiguredBundleRequestTransfer = (new CreateConfiguredBundleRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setConfiguredBundle((new ConfiguredBundleTransfer())->setQuantity(1)->setTemplate($configurableBundleTemplateTransfer))
            ->addItem($firstItemTransfer)
            ->addItem($secondItemTransfer);
    }

    /**
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer
     */
    public function buildUpdateConfiguredBundleRequest(int $quantity = 1): UpdateConfiguredBundleRequestTransfer
    {
        $quoteResponseTransfer = $this->getFacade()->addConfiguredBundle($this->buildCreateConfiguredBundleRequest());

        return $createConfiguredBundleRequestTransfer = (new UpdateConfiguredBundleRequestTransfer())
            ->setQuote($quoteResponseTransfer->getQuoteTransfer())
            ->setQuantity($quantity)
            ->setGroupKey($quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getConfiguredBundle()->getGroupKey());
    }

    /**
     * @param int|null $quantity
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function createConfiguredBundle(?int $quantity = null): ConfiguredBundleTransfer
    {
        return (new ConfiguredBundleBuilder())->build()
            ->setTemplate((new ConfigurableBundleTemplateBuilder())->build()->setUuid('FAKE_CONFIGURABLE_BUNDLE_UUID'))
            ->setGroupKey('FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY')
            ->setQuantity($quantity);
    }

    /**
     * @param string|null $slotUuid
     * @param int|null $quantityPerSlot
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleItemTransfer
     */
    protected function createConfiguredBundleItem(?string $slotUuid = null, ?int $quantityPerSlot = null): ConfiguredBundleItemTransfer
    {
        return (new ConfiguredBundleItemTransfer())
            ->setSlot((new ConfigurableBundleTemplateSlotTransfer())->setUuid($slotUuid))
            ->setQuantityPerSlot($quantityPerSlot);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    protected function createActiveConfigurableBundleTemplate(array $data = []): ConfigurableBundleTemplateTransfer
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
    protected function createConfigurableBundleTemplateSlot(array $data = []): ConfigurableBundleTemplateSlotTransfer
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
    protected function createTemplateTranslationsForAvailableLocales(array $data = []): array
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
    protected function createSlotTranslationsForAvailableLocales(array $data = []): array
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
