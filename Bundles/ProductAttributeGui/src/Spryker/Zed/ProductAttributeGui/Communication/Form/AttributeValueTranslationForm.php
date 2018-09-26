<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 */
class AttributeValueTranslationForm extends AbstractType
{
    public const FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE = 'id_product_management_attribute_value';
    public const FIELD_VALUE = 'value';
    public const FIELD_TRANSLATION = 'translation';
    public const FIELD_FK_LOCALE = 'fk_locale';

    public const GROUP_VALUE_TRANSLATIONS = 'value_translations_group';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addIdField($builder)
            ->addValueField($builder)
            ->addTranslationField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE, HiddenType::class, [
            'label' => null,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_VALUE, TextType::class, [
            'label' => 'Value',
            'disabled' => true,
            'attr' => [
                'readonly' => true,
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTranslationField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_TRANSLATION, TextType::class, [
            'label' => 'Translation',
            'constraints' => [
                new NotBlank([
                    'groups' => self::GROUP_VALUE_TRANSLATIONS,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'value_translation';
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
