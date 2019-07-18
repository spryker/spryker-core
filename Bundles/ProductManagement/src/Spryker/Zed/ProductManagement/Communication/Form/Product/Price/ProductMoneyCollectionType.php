<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product\Price;

use Countable;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractCollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 */
class ProductMoneyCollectionType extends AbstractCollectionType
{
    public const PRICE_DELIMITER = '-';

    protected const PRICE_PRODUCT_VOLUME_EDIT_URL = '/price-product-volume-gui/price-volume/edit';
    protected const PRICE_PRODUCT_VOLUME_ADD_URL = '/price-product-volume-gui/price-volume/add';
    protected const PRICE_PRODUCT_VOLUME_KEY = 'volumePrices';

    /**
     * @var string
     */
    protected static $netPriceModeIdentifier;

    /**
     * @var string
     */
    protected static $grossPriceModeIdentifier;

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $defaultOptions = [
            'entry_options' => [
                'data_class' => MoneyValueTransfer::class,
            ],
            'entry_type' => $this->getFactory()->getMoneyFormTypePlugin()->getType(),
        ];

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $this->setInitialMoneyValueData($event);
            }
        );

        parent::buildForm($builder, array_replace_recursive($defaultOptions, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'product_money_collection';
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    protected function setInitialMoneyValueData(FormEvent $event)
    {
        $moneyCollectionInitialDataProvider = $this->getFactory()->createMoneyCollectionMultiStoreDataProvider();

        if (!($event->getData() instanceof Countable) || count($event->getData()) === 0) {
            $event->setData($moneyCollectionInitialDataProvider->getInitialData());

            return;
        }

        $event->setData(
            $moneyCollectionInitialDataProvider->mergeMissingMoneyValues($event->getData())
        );
    }

    /**
     * Builds table for view:
     * [
     *    'store1' => [
     *       'EUR' => [
     *          'NET_MODE' => [
     *              'DEFAULT' => FormView,
     *              'ORIGINAL' => FormView
     *          ],
     *          'GROSS_MODE' => [
     *              'DEFAULT' => FormView,
     *              'ORIGINAL' => FormView
     *          ]
     *       ],
     *       'USD' => ...
     *    ],
     *    'store2' => ...
     * ]
     *
     * @param \Symfony\Component\Form\FormView $formViewCollection
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function finishView(FormView $formViewCollection, FormInterface $form, array $options)
    {
        $priceTypes = [
            $this->getGrossPriceModeIdentifier() => [],
            $this->getNetPriceModeIdentifier() => [],
        ];

        $priceTable = [];
        $currencies = [];
        $additionalParameters = [];

        foreach ($formViewCollection as $productMoneyTypeFormView) {
            $moneyValueFormView = $productMoneyTypeFormView['moneyValue'];
            $additionalParameters = $this->buildAdditionalParameters($productMoneyTypeFormView, $moneyValueFormView, $additionalParameters);

            $priceTypes = $this->buildPriceTypeList($productMoneyTypeFormView, $priceTypes);
            $priceTable = $this->buildPriceFormViewTable($productMoneyTypeFormView, $moneyValueFormView, $priceTable);

            $currencyTransfer = $this->extractCurrencyTransfer($moneyValueFormView);
            $currencies[$currencyTransfer->getCode()] = $currencyTransfer;
        }

        $this->sortTable($priceTable);

        $formViewCollection->vars['priceTable'] = $priceTable;
        $formViewCollection->vars['priceTypes'] = $priceTypes;
        $formViewCollection->vars['currencies'] = $currencies;

        $formViewCollection->vars = array_merge($additionalParameters, $formViewCollection->vars);
    }

    /**
     * @param \Symfony\Component\Form\FormView $productMoneyTypeFormView
     * @param \Symfony\Component\Form\FormView $moneyValueFormView
     * @param array $additionalParameters
     *
     * @return array
     */
    protected function buildAdditionalParameters(FormView $productMoneyTypeFormView, FormView $moneyValueFormView, array $additionalParameters): array
    {
        $additionalParameters[static::PRICE_PRODUCT_VOLUME_KEY] = $additionalParameters[static::PRICE_PRODUCT_VOLUME_KEY] ?? [];
        $additionalParameters[static::PRICE_PRODUCT_VOLUME_KEY] = $this->buildVolumePriceList($productMoneyTypeFormView, $moneyValueFormView, $additionalParameters[static::PRICE_PRODUCT_VOLUME_KEY]);

        return $additionalParameters;
    }

    /**
     * @param \Symfony\Component\Form\FormView $productMoneyTypeFormView
     * @param \Symfony\Component\Form\FormView $moneyValueFormView
     * @param array $volumePrices
     *
     * @return array
     */
    protected function buildVolumePriceList(FormView $productMoneyTypeFormView, FormView $moneyValueFormView, array $volumePrices): array
    {
        $storeName = $moneyValueFormView->vars['store_name'];
        $currencyIsoCode = $this->extractCurrencyTransfer($moneyValueFormView)->getCode();
        $moneyValueTransfer = $this->extractMoneyValueTransfer($moneyValueFormView);
        $utilEncodingService = $this->getFactory()->getUtilEncoding();
        $priceData = $utilEncodingService->decodeJson($moneyValueTransfer->getPriceData());

        if ($this->isVolumePriceNotApplicable($productMoneyTypeFormView)) {
            return $volumePrices;
        }

        if (!empty($priceData) && isset($priceData->volume_prices)) {
            $volumePrices[$storeName][$currencyIsoCode] = $this
                ->buildVolumePriceData(static::PRICE_PRODUCT_VOLUME_EDIT_URL, 'Edit Product Volume Price');

            return $volumePrices;
        }

        $volumePrices[$storeName][$currencyIsoCode] = $this
            ->buildVolumePriceData(static::PRICE_PRODUCT_VOLUME_ADD_URL, 'Add Product Volume Price');

        return $volumePrices;
    }

    /**
     * @param \Symfony\Component\Form\FormView $productMoneyTypeFormView
     *
     * @return bool
     */
    protected function isVolumePriceNotApplicable(FormView $productMoneyTypeFormView): bool
    {
        $priceProductTransfer = $this->extractPriceProductTransfer($productMoneyTypeFormView);
        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

        if (!$priceProductTransfer->getIdPriceProduct()
            || (!$moneyValueTransfer->getGrossAmount() && !$moneyValueTransfer->getNetAmount())) {
            return true;
        }

        if ($priceProductTransfer->getPriceTypeName() !== $this->getFactory()->getConfig()->getPriceTypeDefault()) {
            return true;
        }

        return false;
    }

    /**
     * @param string $url
     * @param string $title
     *
     * @return string[]
     */
    protected function buildVolumePriceData(string $url, string $title): array
    {
        return [
            'url' => $url,
            'title' => $title,
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormView $productMoneyTypeFormView
     * @param array $priceTypes
     *
     * @return array
     */
    protected function buildPriceTypeList(FormView $productMoneyTypeFormView, array $priceTypes)
    {
        $grossPriceModeIdentifier = $this->getGrossPriceModeIdentifier();
        $netPriceModeIdentifier = $this->getNetPriceModeIdentifier();

        $priceTypeTransfer = $this->extractPriceTypeTransfer($productMoneyTypeFormView);

        $priceType = $priceTypeTransfer->getName();
        $priceModeConfiguration = $priceTypeTransfer->getPriceModeConfiguration();

        if ($priceModeConfiguration === $this->getPriceModeIdentifierForBothType()) {
            $priceTypes[$netPriceModeIdentifier][$priceType] = $priceTypeTransfer;
            $priceTypes[$grossPriceModeIdentifier][$priceType] = $priceTypeTransfer;
        }

        if (!isset($priceTypes[$priceModeConfiguration][$priceType])) {
            $priceTypes[$priceModeConfiguration][$priceType] = $priceTypeTransfer;
        }

        return $priceTypes;
    }

    /**
     * @param \Symfony\Component\Form\FormView $productMoneyTypeFormView
     * @param \Symfony\Component\Form\FormView $moneyValueFormView
     * @param array $priceTable
     *
     * @return array
     */
    protected function buildPriceFormViewTable(
        FormView $productMoneyTypeFormView,
        FormView $moneyValueFormView,
        array $priceTable
    ) {
        $priceTypeTransfer = $this->extractPriceTypeTransfer($productMoneyTypeFormView);

        $grossPriceModeIdentifier = $this->getGrossPriceModeIdentifier();
        $netPriceModeIdentifier = $this->getNetPriceModeIdentifier();

        $priceType = $priceTypeTransfer->getName();
        $priceModeConfiguration = $priceTypeTransfer->getPriceModeConfiguration();

        $storeName = $moneyValueFormView->vars['store_name'];
        $currencyIsoCode = $this->extractCurrencyTransfer($moneyValueFormView)->getCode();

        if ($priceModeConfiguration === $this->getPriceModeIdentifierForBothType()) {
            $priceTable[$storeName][$currencyIsoCode][$netPriceModeIdentifier][$priceType] = $productMoneyTypeFormView;
            $priceTable[$storeName][$currencyIsoCode][$grossPriceModeIdentifier][$priceType] = $productMoneyTypeFormView;
        } else {
            $priceTable[$storeName][$currencyIsoCode][$priceModeConfiguration][$priceType] = $productMoneyTypeFormView;
        }

        return $priceTable;
    }

    /**
     * @param array $priceTable
     *
     * @return void
     */
    protected function sortTable(array &$priceTable)
    {
        foreach ($priceTable as &$current) {
            if (is_array($current)) {
                $this->sortTable($current);
            }
        }
        unset($current);
        ksort($priceTable);
    }

    /**
     * @return string
     */
    protected function getPriceModeIdentifierForBothType()
    {
        return $this->getFactory()->getPriceProductFacade()->getPriceModeIdentifierForBothType();
    }

    /**
     * @return string
     */
    protected function getNetPriceModeIdentifier()
    {
        if (!static::$netPriceModeIdentifier) {
            static::$netPriceModeIdentifier = $this->getFactory()->getPriceFacade()->getNetPriceModeIdentifier();
        }

        return static::$netPriceModeIdentifier;
    }

    /**
     * @return string
     */
    protected function getGrossPriceModeIdentifier()
    {
        if (!static::$grossPriceModeIdentifier) {
            static::$grossPriceModeIdentifier = $this->getFactory()->getPriceFacade()->getGrossPriceModeIdentifier();
        }

        return static::$grossPriceModeIdentifier;
    }

    /**
     * @param int $grossPriceModeIdentifier
     * @param int $netPriceModeIdentifier
     *
     * @return array
     */
    protected function createBasePriceType($grossPriceModeIdentifier, $netPriceModeIdentifier)
    {
        return [
            $grossPriceModeIdentifier => [],
            $netPriceModeIdentifier => [],
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormView $productMoneyTypeFormView
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer
     */
    protected function extractPriceTypeTransfer(FormView $productMoneyTypeFormView)
    {
        return $productMoneyTypeFormView->vars['price_type'];
    }

    /**
     * @param \Symfony\Component\Form\FormView $moneyValueFormView
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function extractMoneyValueTransfer(FormView $moneyValueFormView): MoneyValueTransfer
    {
        return $moneyValueFormView->vars['value'];
    }

    /**
     * @param \Symfony\Component\Form\FormView $productMoneyTypeFormView
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function extractPriceProductTransfer(FormView $productMoneyTypeFormView): PriceProductTransfer
    {
        return $productMoneyTypeFormView->vars['value'];
    }

    /**
     * @param \Symfony\Component\Form\FormView $moneyValueFormView
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function extractCurrencyTransfer(FormView $moneyValueFormView)
    {
        return $this->extractMoneyValueTransfer($moneyValueFormView)->getCurrency();
    }
}
