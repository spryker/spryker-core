<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Page;

use DateTime;
use Spryker\Zed\CmsGui\Communication\Form\ArrayObjectTransformerTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsPageFormType extends AbstractType
{

    const FIELD_SEARCHABLE = 'isSearchable';
    const FIELD_PAGE_ATTRIBUTES = 'pageAttributes';
    const FIELD_PAGE_META_ATTRIBUTES = 'metaAttributes';
    const FIELD_FK_TEMPLATE = 'fkTemplate';
    const FIELD_FK_PAGE = 'fkPage';
    const FIELD_VALID_FROM = 'validFrom';
    const FIELD_VALID_TO = 'validTo';

    const OPTION_TEMPLATE_CHOICES = 'template_choices';
    const OPTION_DATA_CLASS_ATTRIBUTES = 'data_class_attributes';
    const OPTION_DATA_CLASS_META_ATTRIBUTES = 'data_class_meta_attributes';

    use ArrayObjectTransformerTrait;

    /**
     * @var array
     */
    protected $searcableChoices = [
        0 => 'No',
        1 => 'Yes',
    ];

    /**
     * @var \Symfony\Component\Form\FormTypeInterface
     */
    protected $cmsPageAttributesFormType;

    /**
     * @var \Symfony\Component\Form\FormTypeInterface
     */
    protected $cmsPageMetaAttributesFormType;

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $cmsPageAttributesFormType
     * @param \Symfony\Component\Form\FormTypeInterface $cmsPageMetaAttributesFormType
     */
    public function __construct(
        FormTypeInterface $cmsPageAttributesFormType,
        FormTypeInterface $cmsPageMetaAttributesFormType
    ) {
        $this->cmsPageAttributesFormType = $cmsPageAttributesFormType;
        $this->cmsPageMetaAttributesFormType = $cmsPageMetaAttributesFormType;
    }

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
        $builder->add(static::FIELD_SEARCHABLE, ChoiceType::class, [
            'label' => 'Searchable *',
            'choices' => $this->searcableChoices,
        ]);

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
            'label' => 'Template * ',
            'choices' => $choices,

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
            'type' => $this->cmsPageAttributesFormType,
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
            'type' => $this->cmsPageMetaAttributesFormType,
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
        $builder->add(static::FIELD_VALID_FROM, 'date', [
            'widget' => 'single_text',
            'required' => false,
            'attr' => [
                'class' => 'datepicker',
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
        $builder->add(static::FIELD_VALID_TO, 'date', [
            'widget' => 'single_text',
            'required' => false,
            'attr' => [
                'class' => 'datepicker',
            ],
        ]);

        $builder->get(static::FIELD_VALID_TO)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
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
    public function getName()
    {
        return 'cms_page';
    }

}
