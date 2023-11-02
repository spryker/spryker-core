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
 * @method \Spryker\Zed\ProductSearch\ProductSearchConfig getConfig()
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchRepositoryInterface getRepository()
 */
class FilterPreferencesForm extends AbstractAttributeKeyForm
{
    /**
     * @var string
     */
    public const FIELD_ID_PRODUCT_SEARCH_ATTRIBUTE = 'id_product_search_attribute';

    /**
     * @var string
     */
    public const FIELD_FILTER_TYPE = 'filter_type';

    /**
     * @var string
     */
    public const FIELD_TRANSLATIONS = 'translations';

    /**
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'attributeForm';
    }

    /**
     * @deprecated Use {@link getBlockPrefix()} instead.
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
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            static::OPTION_FILTER_TYPE_CHOICES,
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
        $builder->add(static::FIELD_ID_PRODUCT_SEARCH_ATTRIBUTE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addKeyField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_KEY, AutosuggestType::class, [
            'label' => 'Attribute key',
            'url' => '/product-search/filter-preferences/keys',
            'constraints' => $this->createAttributeKeyFieldConstraints(),
            'disabled' => $options[static::OPTION_IS_UPDATE],
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
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addInputTypeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_FILTER_TYPE, ChoiceType::class, [
            'label' => 'Filter type',
            'choices' => array_flip($options[static::OPTION_FILTER_TYPE_CHOICES]),
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addTranslationFields(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_TRANSLATIONS, CollectionType::class, [
            'entry_type' => AttributeTranslationForm::class,
            'entry_options' => $options[static::OPTION_ATTRIBUTE_TRANSLATION_COLLECTION_OPTIONS],
        ]);

        return $this;
    }
}
