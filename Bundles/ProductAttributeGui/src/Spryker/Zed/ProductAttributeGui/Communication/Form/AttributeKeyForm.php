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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 */
class AttributeKeyForm extends AbstractType
{
    public const FIELD_KEY = 'key';
    public const FIELD_KEY_HIDDEN_ID = 'key_hidden_id';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'required' => false,
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
            ->addAttributeKeyField($builder)
            ->addAttributeKeyHiddenField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributeKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_KEY, TextType::class, [
            'label' => 'Attribute key',
            'constraints' => [
                new NotBlank(),
                new Regex([
                    'pattern' => '/^[a-z\-0-9_:]+$/',
                    'message' => 'This field contains illegal characters. It should contain only lower case letters, ' .
                        'digits, numbers, underscores ("_"), hyphens ("-") and colons (":").',
                ]),
            ],
            'attr' => [
                'placeholder' => 'Type first three letters of an existing attribute key for suggestions.',
                'class' => 'kv_attribute_autocomplete',
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
    protected function addAttributeKeyHiddenField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(static::FIELD_KEY_HIDDEN_ID, HiddenType::class, []);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'attribute_form';
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
