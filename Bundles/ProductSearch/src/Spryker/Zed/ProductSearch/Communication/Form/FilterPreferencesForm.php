<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Form;

use Spryker\Zed\ProductSearch\Communication\Form\AttributeTranslationForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class FilterPreferencesForm extends AbstractAttributeKeyForm
{

    const FIELD_ID_PRODUCT_SEARCH_ATTRIBUTE = 'id_product_search_attribute';
    const FIELD_FILTER_TYPE = 'filter_type';
    const FIELD_TRANSLATIONS = 'translations';

    const OPTION_FILTER_TYPE_CHOICES = 'filter_type_choices';
    const OPTION_ATTRIBUTE_TRANSLATION_COLLECTION_OPTIONS = 'attribute_translation_collection_options';

    /**
     * @return string The name of this type
     */
    public function getName()
    {
        return 'attributeForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired([
            self::OPTION_FILTER_TYPE_CHOICES,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addIdProductSearchAttribute($builder)
            ->addKeyField($builder, $options)
            ->addInputTypeField($builder, $options)
            ->addTranslationFields($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductSearchAttribute(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_PRODUCT_SEARCH_ATTRIBUTE, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addInputTypeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_FILTER_TYPE, 'choice', [
            'label' => 'Input type',
            'choices' => $options[self::OPTION_FILTER_TYPE_CHOICES],
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addTranslationFields(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_TRANSLATIONS, 'collection', [
            'type' => new AttributeTranslationForm(),
            'options' => $options[self::OPTION_ATTRIBUTE_TRANSLATION_COLLECTION_OPTIONS],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    protected function getKeyAutosuggestionUrl()
    {
        // TODO: create it's own url
        return '/product-search/filter-preferences/keys';
    }

}
