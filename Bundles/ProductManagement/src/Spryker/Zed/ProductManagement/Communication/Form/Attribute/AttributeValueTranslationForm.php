<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Attribute;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class AttributeValueTranslationForm extends AbstractType
{

    const FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE = 'id_product_management_attribute_value';
    const FIELD_VALUE = 'value';
    const FIELD_TRANSLATION = 'translation';

    const GROUP_VALUE_TRANSLATIONS = 'value_translations_group';

    /**
     * @return string The name of this type
     */
    public function getName()
    {
        return 'value_translation';
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
        $builder->add(self::FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE, 'hidden', [
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
        $builder->add(self::FIELD_VALUE, 'text', [
            'label' => 'Value',
            'read_only' => true,
            'disabled' => true,
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
        $builder->add(self::FIELD_TRANSLATION, 'text', [
            'label' => 'Translation',
            'constraints' => [
                new NotBlank([
                    'groups' => self::GROUP_VALUE_TRANSLATIONS,
                ]),
            ],
        ]);

        return $this;
    }

}
