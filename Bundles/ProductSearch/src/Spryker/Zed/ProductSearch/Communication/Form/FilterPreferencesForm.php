<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductSearch\Communication\ProductSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface getQueryContainer()
 */
class FilterPreferencesForm extends AbstractAttributeKeyForm
{
    public const FIELD_ID_PRODUCT_SEARCH_ATTRIBUTE = 'id_product_search_attribute';
    public const FIELD_FILTER_TYPE = 'filter_type';
    public const FIELD_TRANSLATIONS = 'translations';

    /**
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'attributeForm';
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

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

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
        $builder->add(self::FIELD_ID_PRODUCT_SEARCH_ATTRIBUTE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addKeyField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_KEY, AutosuggestType::class, [
            'label' => 'Attribute key',
            'url' => '/product-search/filter-preferences/keys',
            'constraints' => $this->createAttributeKeyFieldConstraints(),
            'disabled' => $options[self::OPTION_IS_UPDATE],
            'attr' => [
                'placeholder' => 'Type first three letters of an existing attribute key for suggestions.',
            ],
        ]);

        return $this;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function isUniqueKey($key)
    {
        $keyCount = $this->getQueryContainer()
            ->queryProductAttributeKey()
            ->joinSpyProductSearchAttribute()
            ->filterByKey($key)
            ->count();

        return ($keyCount === 0);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addInputTypeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_FILTER_TYPE, ChoiceType::class, [
            'label' => 'Filter type',
            'choices' => array_flip($options[self::OPTION_FILTER_TYPE_CHOICES]),
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
        $builder->add(self::FIELD_TRANSLATIONS, CollectionType::class, [
            'entry_type' => AttributeTranslationForm::class,
            'entry_options' => $options[self::OPTION_ATTRIBUTE_TRANSLATION_COLLECTION_OPTIONS],
        ]);

        return $this;
    }
}
