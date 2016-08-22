<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Spryker\Zed\ProductSearch\Communication\Form\AttributeTranslationForm;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class FilterPreferencesForm extends AbstractType
{

    const FIELD_ID_PRODUCT_SEARCH_ATTRIBUTE = 'id_product_search_attribute';
    const FIELD_KEY = 'key';
    const FIELD_FILTER_TYPE = 'filter_type';
    const FIELD_TRANSLATIONS = 'translations';

    const OPTION_FILTER_TYPE_CHOICES = 'filter_type_choices';
    const OPTION_IS_UPDATE = 'is_update';
    const OPTION_ATTRIBUTE_TRANSLATION_COLLECTION_OPTIONS = 'attribute_translation_collection_options';

    const GROUP_UNIQUE_KEY = 'unique_key_group';

    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface
     */
    protected $productSearchQueryContainer;

    /**
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface $productSearchQueryContainer
     */
    public function __construct(ProductSearchQueryContainerInterface $productSearchQueryContainer)
    {
        $this->productSearchQueryContainer = $productSearchQueryContainer;
    }

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
        $resolver->setRequired([
            self::OPTION_FILTER_TYPE_CHOICES,
        ]);

        $resolver->setDefaults([
            self::OPTION_IS_UPDATE => false,
            self::OPTION_ATTRIBUTE_TRANSLATION_COLLECTION_OPTIONS => [],
            'required' => false,
            'validation_groups' => function (FormInterface $form) {
                $groups = [Constraint::DEFAULT_GROUP];
                $originalData = $form->getConfig()->getData();
                $submittedData = $form->getData();

                if (!isset($originalData[self::FIELD_KEY]) || $submittedData[self::FIELD_KEY] !== $originalData[self::FIELD_KEY]) {
                    $groups[] = self::GROUP_UNIQUE_KEY;
                }

                return $groups;
            },
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
    protected function addKeyField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_KEY, new AutosuggestType(), [
            'label' => 'Attribute key',
            'url' => '/product-search/filter-preferences/keys',
            'constraints' => [
                new NotBlank(),
                new Callback([
                    'methods' => [
                        function ($key, ExecutionContextInterface $context) {
                            $keyCount = $this->productSearchQueryContainer
                                ->queryProductAttributeKey()
                                ->joinSpyProductSearchAttribute()
                                ->filterByKey($key)
                                ->count();

                            if ($keyCount > 0) {
                                $context->addViolation('Attribute key is already used');
                            }
                        },
                    ],
                    'groups' => [self::GROUP_UNIQUE_KEY]
                ]),
            ],
            'disabled' => $options[self::OPTION_IS_UPDATE],
        ]);

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

}
