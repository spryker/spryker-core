<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Form;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer;
use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\ConfigurableBundleGuiConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundleGui\Business\ConfigurableBundleGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleGui\Communication\ConfigurableBundleGuiCommunicationFactory getFactory()
 */
class ConfigurableBundleTemplateSlotEditForm extends AbstractConfigurableBundleTemplateSlotForm
{
    /**
     * @uses \Spryker\Zed\ProductListGui\Communication\Form\ProductListCategoryRelationFormType::OPTION_CATEGORY_IDS
     */
    protected const OPTION_CATEGORY_IDS = ProductListCategoryRelationTransfer::CATEGORY_IDS;

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_CATEGORY_IDS);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->getFactory()
            ->createConfigurableBundleTemplateSlotEditFormExpander()
            ->executeExpanderPlugins($builder, $options);

        $this->setFieldsPropertyPath($builder);
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return [
            static::OPTION_DATA_CLASS => ConfigurableBundleTemplateSlotEditFormTransfer::class,
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function setFieldsPropertyPath(FormBuilderInterface $builder): void
    {
        $builder->get(static::FIELD_NAME)->setPropertyPath(
            $this->getFieldPropertyPath(
                ConfigurableBundleTemplateSlotEditFormTransfer::CONFIGURABLE_BUNDLE_TEMPLATE_SLOT,
                static::FIELD_NAME
            )
        );

        $builder->get(static::FIELD_TRANSLATIONS)->setPropertyPath(
            $this->getFieldPropertyPath(
                ConfigurableBundleTemplateSlotEditFormTransfer::CONFIGURABLE_BUNDLE_TEMPLATE_SLOT,
                static::FIELD_TRANSLATIONS
            )
        );

        $builder->get(ProductListAggregateFormTransfer::PRODUCT_LIST_CATEGORY_RELATION)->setPropertyPath(
            $this->getFieldPropertyPath(
                ConfigurableBundleTemplateSlotEditFormTransfer::PRODUCT_LIST_AGGREGATE_FORM,
                ProductListAggregateFormTransfer::PRODUCT_LIST_CATEGORY_RELATION
            )
        );

        $builder->get(ProductListAggregateFormTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION)->setPropertyPath(
            $this->getFieldPropertyPath(
                ConfigurableBundleTemplateSlotEditFormTransfer::PRODUCT_LIST_AGGREGATE_FORM,
                ProductListAggregateFormTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION
            )
        );

        $builder->get(ProductListAggregateFormTransfer::ASSIGNED_PRODUCT_IDS)->setPropertyPath(
            $this->getFieldPropertyPath(
                ConfigurableBundleTemplateSlotEditFormTransfer::PRODUCT_LIST_AGGREGATE_FORM,
                ProductListAggregateFormTransfer::ASSIGNED_PRODUCT_IDS
            )
        );

        $builder->get(ProductListAggregateFormTransfer::PRODUCT_IDS_TO_BE_ASSIGNED)->setPropertyPath(
            $this->getFieldPropertyPath(
                ConfigurableBundleTemplateSlotEditFormTransfer::PRODUCT_LIST_AGGREGATE_FORM,
                ProductListAggregateFormTransfer::PRODUCT_IDS_TO_BE_ASSIGNED
            )
        );

        $builder->get(ProductListAggregateFormTransfer::PRODUCT_IDS_TO_BE_DE_ASSIGNED)->setPropertyPath(
            $this->getFieldPropertyPath(
                ConfigurableBundleTemplateSlotEditFormTransfer::PRODUCT_LIST_AGGREGATE_FORM,
                ProductListAggregateFormTransfer::PRODUCT_IDS_TO_BE_DE_ASSIGNED
            )
        );
    }

    /**
     * @param string $parent
     * @param string $field
     *
     * @return string
     */
    protected function getFieldPropertyPath(string $parent, string $field): string
    {
        return $parent . '.' . $field;
    }
}
