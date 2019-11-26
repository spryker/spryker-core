<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form;

use Generated\Shared\Transfer\CmsSlotBlockConditionTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\CmsSlotBlockProductCategoryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsSlotBlockProductCategoryGui\Persistence\CmsSlotBlockProductCategoryGuiRepository getRepository()
 */
class ProductCategorySlotBlockConditionForm extends AbstractType
{
    public const OPTION_PRODUCT_ARRAY = 'option-product-array';
    public const OPTION_CATEGORY_ARRAY = 'option-category-array';
    public const OPTION_ALL_ARRAY = 'option-all-array';

    public const FIELD_ALL = CmsSlotBlockConditionTransfer::ALL;
    public const FIELD_CATEGORY_IDS = CmsSlotBlockConditionTransfer::CATEGORY_IDS;
    public const FIELD_PRODUCT_IDS = CmsSlotBlockConditionTransfer::PRODUCT_IDS;

    protected const OPTION_URL_AUTOCOMPLETE = '/cms-slot-block-product-category-gui/product-autocomplete';

    /**
     * @uses \Spryker\Shared\CmsSlotBlockProductCategoryConnector\CmsSlotBlockProductCategoryConnectorConfig::CONDITION_KEY
     */
    protected const FIELD_PRODUCT_CATEGORY = 'productCategory';

    protected const LABEL_PRODUCT_PAGES = 'Products Pages';
    protected const LABEL_PRODUCT_PAGES_PER_CATEGORY = 'Products pages per Category';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addProductCategoryConditionField($builder)
            ->addAllField($builder->get(static::FIELD_PRODUCT_CATEGORY), $options)
            ->addProductIdsField($builder->get(static::FIELD_PRODUCT_CATEGORY), $options)
            ->addCategoryIdsField($builder->get(static::FIELD_PRODUCT_CATEGORY), $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductCategoryConditionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCT_CATEGORY, FormType::class, [
            'label' => false,
            'error_mapping' => [
                '.' => static::FIELD_ALL,
            ],
            'constraints' => [
                $this->getFactory()->createProductCategoryConditionsConstraint(),
            ],
        ]);

        $this->addPreSubmitEventToProductCategoryField($builder->get(static::FIELD_PRODUCT_CATEGORY));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addPreSubmitEventToProductCategoryField(FormBuilderInterface $builder): void
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            $eventData = $event->getData();

            if (!isset($eventData[static::FIELD_PRODUCT_IDS])) {
                return;
            }

            $assignedProductAbstractIds = array_filter(array_values($eventData[static::FIELD_PRODUCT_IDS]));
            $eventData[static::FIELD_PRODUCT_IDS] = $assignedProductAbstractIds;
            $event->getForm()->setData($eventData);
            $event->getForm()->get(static::FIELD_PRODUCT_IDS)->setData($assignedProductAbstractIds);

            $fieldOptions = $event->getForm()->get(static::FIELD_PRODUCT_IDS)->getConfig()->getOptions();
            $this->replaceProductIdsField($event->getForm(), $assignedProductAbstractIds, $fieldOptions);
        });
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAllField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ALL, ChoiceType::class, [
            'required' => false,
            'choices' => $options[static::OPTION_ALL_ARRAY],
            'choice_value' => function ($choice) {
                return $choice ?? true;
            },
            'choice_attr' => function ($choice, $key, $value) {
                return [
                    'data-disable' => $value,
                    'data-inputs' => $this->getFactory()->getUtilEncoding()->encodeJson([static::FIELD_PRODUCT_IDS, static::FIELD_CATEGORY_IDS]),
                ];
            },
            'expanded' => true,
            'multiple' => false,
            'placeholder' => false,
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductIdsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PRODUCT_IDS, Select2ComboBoxType::class, [
            'label' => static::LABEL_PRODUCT_PAGES,
            'multiple' => true,
            'required' => false,
            'choices' => $options[static::OPTION_PRODUCT_ARRAY],
            'attr' => [
                'data-autocomplete-url' => static::OPTION_URL_AUTOCOMPLETE,
            ],
        ]);

        $this->addPreSetDataEventToProductIdsField($builder->get(static::FIELD_PRODUCT_IDS));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addPreSetDataEventToProductIdsField(FormBuilderInterface $builder): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            if (!$event->getData()) {
                return;
            }

            $assignedProductAbstractIds = array_filter(array_values($event->getData()));

            $this->replaceProductIdsField(
                $event->getForm()->getParent(),
                $assignedProductAbstractIds,
                $event->getForm()->getConfig()->getOptions()
            );
        });
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCategoryIdsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_CATEGORY_IDS, Select2ComboBoxType::class, [
            'label' => static::LABEL_PRODUCT_PAGES_PER_CATEGORY,
            'choices' => $options[static::OPTION_CATEGORY_ARRAY],
            'required' => false,
            'multiple' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productForm
     * @param int[] $assignedProductAbstractIds
     * @param array $options
     *
     * @return void
     */
    protected function replaceProductIdsField(
        FormInterface $productForm,
        array $assignedProductAbstractIds,
        array $options
    ): void {
        $options['choices'] = $this->getChoicesByProductAbstractIds($assignedProductAbstractIds);
        $productForm->add(static::FIELD_PRODUCT_IDS, Select2ComboBoxType::class, $options);
    }

    /**
     * @param int[] $assignedProductAbstractIds
     *
     * @return array
     */
    protected function getChoicesByProductAbstractIds(array $assignedProductAbstractIds): array
    {
        $options = $this->getFactory()
            ->createProductCategorySlotBlockDataProvider()
            ->getOptions($assignedProductAbstractIds);

        return $options[static::OPTION_PRODUCT_ARRAY];
    }
}
