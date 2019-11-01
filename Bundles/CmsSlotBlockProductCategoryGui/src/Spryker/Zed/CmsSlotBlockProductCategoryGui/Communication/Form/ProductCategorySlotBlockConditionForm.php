<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form;

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
 */
class ProductCategorySlotBlockConditionForm extends AbstractType
{
    public const OPTION_PRODUCT_ARRAY = 'option-product-array';
    public const OPTION_CATEGORY_ARRAY = 'option-category-array';
    protected const OPTION_URL_AUTOCOMPLETE = '/cms-slot-block-product-category-gui/product-autocomplete';

    /**
     * @uses \Spryker\Shared\CmsSlotBlockProductCategoryGui\CmsSlotBlockProductCategoryGuiConfig::CONDITION_KEY
     */
    protected const FIELD_PRODUCT_CATEGORY = 'productCategory';
    protected const FIELD_ALL = 'all';
    protected const FIELD_PRODUCT_IDS = 'productIds';
    protected const FIELD_CATEGORY_IDS = 'categoryIds';

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
        $this->addProductConditionField($builder)
            ->addAllField($builder->get(static::FIELD_PRODUCT_CATEGORY))
            ->addProductIdsField($builder->get(static::FIELD_PRODUCT_CATEGORY), $options)
            ->addCategoryIdsField($builder->get(static::FIELD_PRODUCT_CATEGORY), $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductConditionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCT_CATEGORY, FormType::class, [
            'label' => false,
            'error_mapping' => [
                '.' => static::FIELD_ALL,
            ],
            'constraints' => [
                $this->getFactory()->createProductCategoryConditionsConstraint(),
            ],
        ])->get(static::FIELD_PRODUCT_CATEGORY)->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            $eventData = $event->getData();

            if (!isset($eventData[static::FIELD_PRODUCT_IDS])) {
                return;
            }

            $assignedAbstractProductIds = array_filter(array_values($eventData[static::FIELD_PRODUCT_IDS]));
            $eventData[static::FIELD_PRODUCT_IDS] = $assignedAbstractProductIds;
            $event->getForm()->setData($eventData);
            $event->getForm()->get(static::FIELD_PRODUCT_IDS)->setData($assignedAbstractProductIds);

            $fieldOptions = $event->getForm()->get(static::FIELD_PRODUCT_IDS)->getConfig()->getOptions();
            $this->replaceFieldProductIds($event->getForm(), $assignedAbstractProductIds, $fieldOptions);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAllField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ALL, ChoiceType::class, [
            'required' => false,
            'choices' => [
                'All Product Pages' => true,
                'Specific Product Pages' => false,
            ],
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
        ])->get(static::FIELD_PRODUCT_IDS)->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            if (!$event->getData()) {
                return;
            }

            $assignedAbstractProductIds = array_filter(array_values($event->getData()));

            $this->replaceFieldProductIds(
                $event->getForm()->getParent(),
                $assignedAbstractProductIds,
                $event->getForm()->getConfig()->getOptions()
            );
        });

        return $this;
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
     * @param int[] $assignedAbstractProductIds
     * @param array $options
     *
     * @return void
     */
    protected function replaceFieldProductIds(
        FormInterface $productForm,
        array $assignedAbstractProductIds,
        array $options
    ): void {
        $options['choices'] = $this->getChoicesOptionsByAbstractProductIds($assignedAbstractProductIds);
        $productForm->add(static::FIELD_PRODUCT_IDS, Select2ComboBoxType::class, $options);
    }

    /**
     * @param int[] $assignedAbstractProductIds
     *
     * @return array
     */
    protected function getChoicesOptionsByAbstractProductIds(array $assignedAbstractProductIds): array
    {
        $options = $this->getFactory()
            ->createProductCategorySlotBlockDataProvider()
            ->getOptions($assignedAbstractProductIds);

        return $options[static::OPTION_PRODUCT_ARRAY];
    }
}
