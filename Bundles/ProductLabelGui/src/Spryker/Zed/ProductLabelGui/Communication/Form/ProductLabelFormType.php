<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form;

use DateTime;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\FormattedNumberType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductLabelGui\Communication\ProductLabelGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelGui\ProductLabelGuiConfig getConfig()
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiRepositoryInterface getRepository()
 */
class ProductLabelFormType extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_NAME = 'name';

    /**
     * @var string
     */
    public const FIELD_EXCLUSIVE_FLAG = 'isExclusive';

    /**
     * @var string
     */
    public const FIELD_DYNAMIC_FLAG = 'isDynamic';

    /**
     * @var string
     */
    public const FIELD_PRIORITY = 'position';

    /**
     * @var string
     */
    public const FIELD_STORE_RELATION = 'storeRelation';

    /**
     * @var string
     */
    public const FIELD_STATUS_FLAG = 'isActive';

    /**
     * @var string
     */
    public const FIELD_VALID_FROM_DATE = 'validFrom';

    /**
     * @var string
     */
    public const FIELD_VALID_TO_DATE = 'validTo';

    /**
     * @var string
     */
    public const FIELD_FRONT_END_REFERENCE = 'frontEndReference';

    /**
     * @var string
     */
    public const FIELD_LOCALIZED_ATTRIBUTES = 'localizedAttributes';

    /**
     * @var string
     */
    public const OPTION_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const VALIDITY_DATE_FORMAT = 'Y-m-d';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ProductLabelTransfer::class,
            'constraints' => [
                $this->getFactory()->createUniqueProductLabelNameConstraint(),
            ],
            static::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addNameField($builder)
            ->addStatusFlagField($builder)
            ->addExclusiveFlagField($builder)
            ->addValidFromField($builder)
            ->addPriorityField($builder, $options)
            ->addValidToField($builder)
            ->addFontEndReferenceField($builder)
            ->addLocalizedAttributesSubForm($builder)
            ->addDynamicFlagField($builder)
            ->addStoreRelationField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_NAME,
            TextType::class,
            [
                'label' => 'Name',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addExclusiveFlagField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_EXCLUSIVE_FLAG,
            CheckboxType::class,
            [
                'label' => 'Is Exclusive',
                'required' => false,
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStatusFlagField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_STATUS_FLAG,
            CheckboxType::class,
            [
                'label' => 'Is Active',
                'required' => false,
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDynamicFlagField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_DYNAMIC_FLAG,
            CheckboxType::class,
            [
              'label' => 'Is Dynamic',
              'required' => false,
              'disabled' => true,
              'attr' => [
                  'readonly' => true,
              ],
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addPriorityField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_PRIORITY,
            FormattedNumberType::class,
            [
                'label' => 'Priority',
                'locale' => $options[static::OPTION_LOCALE],
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual(['value' => 1]),
                ],
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStoreRelationField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_STORE_RELATION,
            $this->getFactory()->getStoreRelationFormTypePlugin()->getType(),
            [
                'label' => false,
                'required' => false,
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidFromField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_VALID_FROM_DATE,
            DateType::class,
            [
                'label' => 'Valid From',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'js-valid-from-date-picker safe-datetime',
                ],
            ],
        );

        $this->addDateTimeTransformer(static::FIELD_VALID_FROM_DATE, $builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidToField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_VALID_TO_DATE,
            DateType::class,
            [
                'label' => 'Valid To',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'js-valid-to-date-picker safe-datetime',
                ],
            ],
        );

        $this->addDateTimeTransformer(static::FIELD_VALID_TO_DATE, $builder);

        return $this;
    }

    /**
     * @param string $fieldName
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addDateTimeTransformer($fieldName, FormBuilderInterface $builder)
    {
        $builder
            ->get($fieldName)
            ->addModelTransformer(new CallbackTransformer(
                function ($dateAsString) {
                    if (!$dateAsString) {
                        return null;
                    }

                    return new DateTime($dateAsString);
                },
                function ($dateAsObject) {
                    if (!$dateAsObject) {
                        return null;
                    }

                    return $dateAsObject->format(static::VALIDITY_DATE_FORMAT);
                },
            ));
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFontEndReferenceField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_FRONT_END_REFERENCE,
            TextType::class,
            [
                'label' => 'Front-end Reference',
                'required' => false,
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocalizedAttributesSubForm(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_LOCALIZED_ATTRIBUTES,
            CollectionType::class,
            [
                'entry_type' => ProductLabelLocalizedAttributesFormType::class,
                'property_path' => 'localizedAttributesCollection',
            ],
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'productLabel';
    }
}
