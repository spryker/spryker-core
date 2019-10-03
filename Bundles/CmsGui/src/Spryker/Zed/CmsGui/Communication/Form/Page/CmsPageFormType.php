<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Page;

use DateTime;
use Spryker\Zed\CmsGui\Communication\Form\ArrayObjectTransformerTrait;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsGui\CmsGuiConfig getConfig()
 */
class CmsPageFormType extends AbstractType
{
    use ArrayObjectTransformerTrait;

    public const FIELD_SEARCHABLE = 'isSearchable';
    public const FIELD_PAGE_ATTRIBUTES = 'pageAttributes';
    public const FIELD_PAGE_META_ATTRIBUTES = 'metaAttributes';
    public const FIELD_FK_TEMPLATE = 'fkTemplate';
    public const FIELD_FK_PAGE = 'fkPage';
    public const FIELD_VALID_FROM = 'validFrom';
    public const FIELD_VALID_TO = 'validTo';

    public const OPTION_TEMPLATE_CHOICES = 'template_choices';
    public const OPTION_DATA_CLASS_ATTRIBUTES = 'data_class_attributes';
    public const OPTION_DATA_CLASS_META_ATTRIBUTES = 'data_class_meta_attributes';
    public const FIELD_STORE_RELATION = 'storeRelation';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_TEMPLATE_CHOICES);
        $resolver->setRequired(static::OPTION_DATA_CLASS_ATTRIBUTES);
        $resolver->setRequired(static::OPTION_DATA_CLASS_META_ATTRIBUTES);
        $resolver->setRequired(CmsPageAttributesFormType::OPTION_AVAILABLE_LOCALES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addSearchableField($builder)
            ->addStoreRelationForm($builder)
            ->addFkPage($builder)
            ->addFkTemplateField($builder, $options[static::OPTION_TEMPLATE_CHOICES])
            ->addPageAttributesFormCollection($builder, $options)
            ->addPageMetaAttribuesFormCollection($builder, $options)
            ->addValidFromField($builder)
            ->addValidToField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkPage(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_PAGE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSearchableField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_SEARCHABLE,
            CheckboxType::class,
            ['required' => false]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addFkTemplateField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_FK_TEMPLATE, ChoiceType::class, [
            'label' => 'Template',
            'choices' => array_flip($choices),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPageAttributesFormCollection(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PAGE_ATTRIBUTES, CollectionType::class, [
            'entry_type' => CmsPageAttributesFormType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                'data_class' => $options[static::OPTION_DATA_CLASS_ATTRIBUTES],
                CmsPageAttributesFormType::OPTION_AVAILABLE_LOCALES => $options[CmsPageAttributesFormType::OPTION_AVAILABLE_LOCALES],
            ],
        ]);

        $builder->get(static::FIELD_PAGE_ATTRIBUTES)
            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPageMetaAttribuesFormCollection(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PAGE_META_ATTRIBUTES, CollectionType::class, [
            'entry_type' => CmsPageMetaAttributesFormType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                'data_class' => $options[static::OPTION_DATA_CLASS_META_ATTRIBUTES],
            ],
        ]);

        $builder->get(static::FIELD_PAGE_META_ATTRIBUTES)
            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidFromField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_VALID_FROM, DateType::class, [
            'widget' => 'single_text',
            'required' => false,
            'attr' => [
                'class' => 'datepicker safe-datetime',
            ],
            'constraints' => [
                 $this->createValidFromRangeConstraint(),
            ],
        ]);

        $builder->get(static::FIELD_VALID_FROM)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidToField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_VALID_TO, DateType::class, [
            'widget' => 'single_text',
            'required' => false,
            'attr' => [
                'class' => 'datepicker safe-datetime',
            ],
            'constraints' => [
                $this->createValidToFieldRangeConstraint(),
            ],
        ]);

        $builder->get(static::FIELD_VALID_TO)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStoreRelationForm(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_STORE_RELATION,
            $this->getFactory()->getStoreRelationFormTypePlugin()->getType(),
            [
                'label' => false,
                'required' => false,
            ]
        );

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createValidFromRangeConstraint()
    {
        return new Callback([
            'callback' => function ($dateTimeFrom, ExecutionContextInterface $context) {
                $cmsPageTransfer = $context->getRoot()->getData();
                if (!$dateTimeFrom) {
                    if ($cmsPageTransfer->getValidTo()) {
                        $context->addViolation('This field should be selected if "Valid to" is filled.');
                    }

                    return;
                }

                if ($dateTimeFrom > $cmsPageTransfer->getValidTo()) {
                    $context->addViolation('Date "Valid from" cannot be later than "Valid to".');
                }

                if ($dateTimeFrom == $cmsPageTransfer->getValidTo()) {
                    $context->addViolation('Date "Valid from" is the same as "Valid to".');
                }
            },
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createValidToFieldRangeConstraint()
    {
        return new Callback([
           'callback' => function ($dateTimeTo, ExecutionContextInterface $context) {

                $cmsPageTransfer = $context->getRoot()->getData();

            if (!$dateTimeTo) {
                if ($cmsPageTransfer->getValidFrom()) {
                    $context->addViolation('This field should be selected if "Valid from" is filled.');
                }

                return;
            }

            if ($dateTimeTo < $cmsPageTransfer->getValidFrom()) {
                $context->addViolation('Date "Valid to" cannot be earlier than "Valid from".');
            }
           },
        ]);
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createDateTimeModelTransformer()
    {
        return new CallbackTransformer(
            function ($value) {
                if ($value !== null) {
                    return new DateTime($value);
                }
            },
            function ($value) {
                return $value;
            }
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'cms_page';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
