<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableEditableButtonTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\ProductAbstractNotFoundException;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAbstractForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Grouper\ProductAttributeGrouperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface;

class ProductAbstractAttributeGuiTableConfigurationProvider implements ProductAbstractAttributeGuiTableConfigurationProviderInterface
{
    /**
     * @var string
     *
     * @uses \Spryker\Shared\ProductAttribute\ProductAttributeConfig::INPUT_TYPE_MULTISELECT
     */
    protected const INPUT_TYPE_MULTISELECT = 'multiselect';

    /**
     * @var string
     */
    protected const KEY_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const KEY_PRODUCT_ATTRIBUTES = 'productAttributes';

    /**
     * @var string
     */
    protected const KEY_DATA = 'data';

    /**
     * @var string
     */
    protected const KEY_ERRORS = 'errors';

    /**
     * @var string
     */
    protected const KEY_CONTEXT = 'context';

    /**
     * @var string
     */
    protected const COL_KEY_ATTRIBUTE_KEY = 'attribute_key';

    /**
     * @var string
     */
    protected const KEY_PRODUCT_MANAGEMENT_ATTRIBUTES = 'productManagementAttributes';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\ProductAttributesController::PARAM_ID_PRODUCT_ABSTRACT
     *
     * @var string
     */
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'idProductAbstract';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\ProductAttributesController::PARAM_ATTRIBUTE_NAME
     *
     * @var string
     */
    protected const PARAM_ATTRIBUTE_NAME = 'attribute_name';

    /**
     * @var string
     */
    public const COL_KEY_ID_PRODUCT_ABSTRACT = 'idProductAbstract';

    /**
     * @var string
     */
    public const COL_KEY_ATTRIBUTE_NAME = 'attribute_name';

    /**
     * @var string
     */
    public const COL_KEY_ATTRIBUTE_DEFAULT = 'attribute_default';

    /**
     * @var string
     */
    protected const TITLE_COLUMN_ATTRIBUTE_NAME = 'Attribute';

    /**
     * @var string
     */
    protected const TITLE_COLUMN_ATTRIBUTE_DEFAULT = 'Default';

    /**
     * @var string
     */
    protected const ID_ROW_ACTION_DELETE = 'delete-attribute';

    /**
     * @var string
     */
    protected const TITLE_ROW_ACTION_DELETE = 'Delete';

    /**
     * @var string
     */
    protected const TITLE_EDITABLE_BUTTON = 'Add';

    /**
     * @var string
     */
    protected const VARIANT_EDITABLE_BUTTON = 'outline';

    /**
     * @var string
     */
    protected const SIZE_EDITABLE_BUTTON = 'sm';

    /**
     * @var string
     */
    protected const FORMAT_STRING_DATA_URL = '%s?%s=%s';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\ProductAttributesController::attributeDataAction()
     *
     * @var string
     */
    protected const PRODUCT_ATTRIBUTES_DATA_URL = '/product-merchant-portal-gui/product-attributes/attribute-data/';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\ProductAttributesController::saveAction()
     *
     * @var string
     */
    protected const PRODUCT_ATTRIBUTE_SAVE_DATA_URL = '/product-merchant-portal-gui/product-attributes/save';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\ProductAttributesController::deleteAction()
     *
     * @var string
     */
    protected const PRODUCT_ATTRIBUTE_DELETE_URL = '/product-merchant-portal-gui/product-attributes/delete';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\ProductAttributesController::tableDataAction()
     *
     * @var string
     */
    protected const PRODUCT_ATTRIBUTES_TABLE_DATA_URL = '/product-merchant-portal-gui/product-attributes/table-data';

    /**
     * @var string
     */
    protected const PLACEHOLDER_SELECT_ATTRIBUTE = 'Select';

    /**
     * @var string
     */
    protected const COLOR_GREY = 'gray';

    /**
     * @var string
     */
    protected const COLOR_BLUE = 'blue';

    /**
     * @var string
     */
    protected const EDITABLE_NEW_ROW = 'editableNewRow';

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected GuiTableFactoryInterface $guiTableFactory;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface
     */
    protected ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface
     */
    protected ProductMerchantPortalGuiToProductFacadeInterface $productFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Grouper\ProductAttributeGrouperInterface
     */
    protected ProductAttributeGrouperInterface $productAttributeGrouper;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Grouper\ProductAttributeGrouperInterface $productAttributeGrouper
     */
    public function __construct(
        GuiTableFactoryInterface $guiTableFactory,
        ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade,
        ProductMerchantPortalGuiToProductFacadeInterface $productFacade,
        ProductAttributeGrouperInterface $productAttributeGrouper
    ) {
        $this->guiTableFactory = $guiTableFactory;
        $this->productAttributeFacade = $productAttributeFacade;
        $this->productFacade = $productFacade;
        $this->productAttributeGrouper = $productAttributeGrouper;
    }

    /**
     * @param int $idProductAbstract
     * @param array<string, array<int|string, mixed>> $attributesInitialData
     *
     * @throws \Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\ProductAbstractNotFoundException
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(
        int $idProductAbstract,
        array $attributesInitialData
    ): GuiTableConfigurationTransfer {
        $productAbstractTransfer = $this->productFacade->findProductAbstractById($idProductAbstract);

        if (!$productAbstractTransfer) {
            throw new ProductAbstractNotFoundException($idProductAbstract);
        }

        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder
            ->addColumnChip(static::COL_KEY_ATTRIBUTE_NAME, static::TITLE_COLUMN_ATTRIBUTE_NAME, true, false, static::COLOR_GREY)
            ->addColumnChip(static::COL_KEY_ATTRIBUTE_DEFAULT, static::TITLE_COLUMN_ATTRIBUTE_DEFAULT, true, false, static::COLOR_BLUE);

        $dataSourceUrl = sprintf(
            static::FORMAT_STRING_DATA_URL,
            static::PRODUCT_ATTRIBUTES_TABLE_DATA_URL,
            static::COL_KEY_ID_PRODUCT_ABSTRACT,
            $idProductAbstract,
        );

        $guiTableConfigurationBuilder->setDataSourceUrl($dataSourceUrl)
            ->setIsPaginationEnabled(false)
            ->isSearchEnabled(false);

        $guiTableConfigurationBuilder = $this->addEditableColumns($guiTableConfigurationBuilder, $attributesInitialData);
        $guiTableConfigurationBuilder = $this->addRowActions($guiTableConfigurationBuilder);
        $guiTableConfigurationTransfer = $guiTableConfigurationBuilder->createConfiguration();

        $guiTableConfigurationTransfer->getEditableOrFail()->getUpdateOrFail()->addDisableForCol(static::COL_KEY_ATTRIBUTE_NAME);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     * @param array<string, array<string, mixed>> $attributesInitialData
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addCustomEditableColumns(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder,
        array $attributesInitialData
    ): GuiTableConfigurationBuilderInterface {
        $productManagementAttributeTransfers = $this->getProductManagementAttributes($attributesInitialData);
        $productAttributes = $this->getInitialDataContext($attributesInitialData, static::KEY_PRODUCT_ATTRIBUTES) ?? [];
        $localeTransfer = $this->getInitialDataContext($attributesInitialData, static::KEY_LOCALE);

        $applicableProductManagementAttributesGroupedByProductAttributeKey = $this->productAttributeGrouper->getApplicableProductManagementAttributesGroupedByProductAttributeKey(
            $productManagementAttributeTransfers,
            $productAttributes,
        );

        $attributesInitialData = $this->productAttributeGrouper->getInitialDataGroupedByAttributeKey(
            $attributesInitialData,
            $applicableProductManagementAttributesGroupedByProductAttributeKey,
        );

        $attributesOptions = $this->productAttributeGrouper->getLocalizedAttributeNamesGroupedByProductAttributeKey($applicableProductManagementAttributesGroupedByProductAttributeKey, $localeTransfer);

        foreach ($attributesInitialData[static::KEY_DATA] as &$attributesInitialDatum) {
            $attributesInitialDatum[static::COL_KEY_ATTRIBUTE_NAME] = $attributesOptions[$attributesInitialDatum[static::COL_KEY_ATTRIBUTE_KEY]] ?? $attributesInitialDatum[static::COL_KEY_ATTRIBUTE_NAME];
        }

        $guiTableConfigurationBuilder->addInlineEditableColumnDynamic(
            static::COL_KEY_ATTRIBUTE_DEFAULT,
            static::TITLE_COLUMN_ATTRIBUTE_DEFAULT,
            static::COL_KEY_ATTRIBUTE_NAME,
            $this->buildEditableTypeOptions($attributesInitialData, $applicableProductManagementAttributesGroupedByProductAttributeKey),
            $this->getDefaultTextTypeOptions(),
        );

        $guiTableConfigurationBuilder->enableInlineDataEditing($this->getAttributeActionUrl(static::PRODUCT_ATTRIBUTE_SAVE_DATA_URL));

        $formInputName = sprintf('%s[%s][]', ProductAbstractForm::BLOCK_PREFIX, ProductAbstractTransfer::ATTRIBUTES);

        $guiTableConfigurationBuilder->enableAddingNewRows(
            $formInputName,
            $attributesInitialData,
            [
                GuiTableEditableButtonTransfer::TITLE => static::TITLE_EDITABLE_BUTTON,
                GuiTableEditableButtonTransfer::VARIANT => static::VARIANT_EDITABLE_BUTTON,
                GuiTableEditableButtonTransfer::SIZE => static::SIZE_EDITABLE_BUTTON,
            ],
        );

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param array<int|string, mixed> $attributesInitialData
     * @param array<string, \Generated\Shared\Transfer\ProductManagementAttributeTransfer> $applicableProductManagementAttributesGroupedByProductAttributeKey
     *
     * @return array<int|string, mixed>
     */
    protected function buildEditableTypeOptions(array $attributesInitialData, array $applicableProductManagementAttributesGroupedByProductAttributeKey): array
    {
        $typeOptions = [];

        $attributesInitialData = $this->productAttributeGrouper->getInitialDataGroupedByAttributeKey(
            $attributesInitialData,
            $applicableProductManagementAttributesGroupedByProductAttributeKey,
        );

        foreach ($attributesInitialData[static::KEY_DATA] ?? [] as $attributesInitialDatum) {
            $attributeKey = $attributesInitialDatum[static::COL_KEY_ATTRIBUTE_KEY];
            $productManagementAttributeTransfer = $applicableProductManagementAttributesGroupedByProductAttributeKey[$attributeKey] ?? null;

            if (!$productManagementAttributeTransfer) {
                continue;
            }

            if ($productManagementAttributeTransfer->getAllowInput()) {
                continue;
            }

            $attributeTypeOptions = [
                'options' => $this->getOptionsFromProductManagementAttribute($productManagementAttributeTransfer),
                'multiple' => $productManagementAttributeTransfer->getInputType() === static::INPUT_TYPE_MULTISELECT,
            ];

            $typeOptions[$attributeKey] = [
                'type' => GuiTableConfigurationBuilderInterface::COLUMN_TYPE_SELECT,
                'typeOptions' => $attributeTypeOptions,
            ];
        }

        return $typeOptions;
    }

    /**
     * @return array<string, string>
     */
    protected function getDefaultTextTypeOptions(): array
    {
        return [
            'type' => GuiTableConfigurationBuilderInterface::COLUMN_TYPE_INPUT,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttribute
     *
     * @return array<int, array<string, string>>
     */
    protected function getOptionsFromProductManagementAttribute(ProductManagementAttributeTransfer $productManagementAttribute): array
    {
        $options = [];
        foreach ($productManagementAttribute->getValues() as $value) {
            if ($value->getValue() === null) {
                continue;
            }
            $options[] = [
                'value' => $value->getValueOrFail(),
                'title' => ucfirst($value->getValueOrFail()),
            ];
        }

        return $options;
    }

    /**
     * @param array<string, mixed> $initialData
     *
     * @return array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer>
     */
    protected function getProductManagementAttributes(array $initialData): array
    {
        $productManagementAttributes = $this->getInitialDataContext($initialData, static::KEY_PRODUCT_MANAGEMENT_ATTRIBUTES);
        if ($productManagementAttributes !== null) {
            return $productManagementAttributes;
        }

        return $this->productAttributeFacade->getProductAttributeCollection();
    }

    /**
     * @param array<string, mixed> $initialData
     * @param string $key
     *
     * @return mixed
     */
    protected function getInitialDataContext(array $initialData, string $key): mixed
    {
        return $initialData[static::KEY_CONTEXT][$key] ?? null;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     * @param array<string, array<string|int, mixed>> $attributesInitialData
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addEditableColumns(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder,
        array $attributesInitialData
    ): GuiTableConfigurationBuilderInterface {
        $allAttributes = $this->productAttributeFacade->getProductAttributeCollection();

        $attributesOptions = [];
        foreach ($allAttributes as $attribute) {
            $attributesOptions[$attribute->getKeyOrFail()] = $attribute->getKeyOrFail();
        }

        $productManagementAttributeTransfers = $this->getProductManagementAttributes($attributesInitialData);
        $productAttributes = $this->getInitialDataContext($attributesInitialData, static::KEY_PRODUCT_ATTRIBUTES) ?? [];

        $applicableProductManagementAttributesGroupedByProductAttributeKey = $this->productAttributeGrouper->getApplicableProductManagementAttributesGroupedByProductAttributeKey(
            $productManagementAttributeTransfers,
            $productAttributes,
        );

        $guiTableConfigurationBuilder->addEditableColumnSelect(
            static::COL_KEY_ATTRIBUTE_NAME,
            static::TITLE_COLUMN_ATTRIBUTE_NAME,
            false,
            $attributesOptions,
            static::PLACEHOLDER_SELECT_ATTRIBUTE,
        )->addInlineEditableColumnDynamic(
            static::COL_KEY_ATTRIBUTE_DEFAULT,
            static::TITLE_COLUMN_ATTRIBUTE_DEFAULT,
            static::COL_KEY_ATTRIBUTE_NAME,
            $this->buildEditableTypeOptions($attributesInitialData, $applicableProductManagementAttributesGroupedByProductAttributeKey),
            $this->getDefaultTextTypeOptions(),
        );

        $guiTableConfigurationBuilder->enableInlineDataEditing($this->getAttributeActionUrl(static::PRODUCT_ATTRIBUTE_SAVE_DATA_URL));

        $formInputName = sprintf('%s[%s]', ProductAbstractForm::BLOCK_PREFIX, ProductAbstractTransfer::ATTRIBUTES);

        $guiTableConfigurationBuilder->enableAddingNewRows(
            $formInputName,
            $attributesInitialData,
            [
                GuiTableEditableButtonTransfer::TITLE => static::TITLE_EDITABLE_BUTTON,
                GuiTableEditableButtonTransfer::VARIANT => static::VARIANT_EDITABLE_BUTTON,
                GuiTableEditableButtonTransfer::SIZE => static::SIZE_EDITABLE_BUTTON,
            ],
        );

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addRowActions(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addRowActionHttp(
            static::ID_ROW_ACTION_DELETE,
            static::TITLE_ROW_ACTION_DELETE,
            $this->getAttributeActionUrl(static::PRODUCT_ATTRIBUTE_DELETE_URL),
        );

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param string $action
     *
     * @return string
     */
    protected function getAttributeActionUrl(string $action): string
    {
        return sprintf(
            '%s?%s=${row.%s}&%s=${row.%s}',
            $action,
            static::PARAM_ATTRIBUTE_NAME,
            static::COL_KEY_ATTRIBUTE_NAME,
            static::PARAM_ID_PRODUCT_ABSTRACT,
            static::COL_KEY_ID_PRODUCT_ABSTRACT,
        );
    }
}
