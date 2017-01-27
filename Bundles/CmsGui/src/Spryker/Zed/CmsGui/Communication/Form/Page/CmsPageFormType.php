<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Page;

use ArrayObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsPageFormType extends AbstractType
{
    const FIELD_SEARCHABLE = 'isSearchable';
    const FIELD_PAGE_ATTRIBUTES = 'pageAttributes';
    const FIELD_PAGE_META_ATTRIBUTES = 'metaAttributes';
    const FIELD_FK_TEMPLATE = 'fkTemplate';
    const FIELD_FK_PAGE = 'fkPage';

    const OPTION_TEMPLATE_CHOICES = 'template_choices';

    /**
     * @var array
     */
    protected $searcableChoices = [
        0 => 'No',
        1 => 'Yes'
    ];

    /**
     * @var \Spryker\Zed\CmsGui\Communication\Form\CmsPageAttributesFormType
     */
    protected $cmsPageAttributesFormType;

    /**
     * @var \Spryker\Zed\CmsGui\Communication\Form\CmsPageMetaAttributesFormType
     */
    protected $cmsPageMetaAttributesFormType;

    /**
     * @param \Spryker\Zed\CmsGui\Communication\Form\CmsPageAttributesFormType $cmsPageAttributesFormType
     * @param \Spryker\Zed\CmsGui\Communication\Form\CmsPageMetaAttributesFormType $cmsPageMetaAttributesFormType
     */
    public function __construct(
        CmsPageAttributesFormType $cmsPageAttributesFormType,
        CmsPageMetaAttributesFormType $cmsPageMetaAttributesFormType
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
            ->addPageMetaAttribuesFormCollection($builder);
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
            'choices' => $this->searcableChoices
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
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
        ]);

        $builder->get(self::FIELD_PAGE_ATTRIBUTES)
            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPageMetaAttribuesFormCollection(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PAGE_META_ATTRIBUTES, CollectionType::class, [
            'type' => $this->cmsPageMetaAttributesFormType,
            'allow_add' => true,
            'allow_delete' => true,
        ]);

        $builder->get(self::FIELD_PAGE_META_ATTRIBUTES)
            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createArrayObjectModelTransformer()
    {
        return new CallbackTransformer(
            function ($value) {
                return (array)$value;
            },
            function($value) {
                return new ArrayObject($value);
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
