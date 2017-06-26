<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AttributeKeyForm extends AbstractType
{
    const FIELD_KEY = 'key';

    const GROUP_UNIQUE_KEY = 'unique_key_group';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'required' => true,
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'attribute_form';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this
            ->addAttributeKeyField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributeKeyField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_KEY, new AutosuggestType(), [
            'label' => 'Attribute key *',
            'url' => '/product-attribute-gui/suggest/keys',
            'constraints' => $this->createAttributeKeyFieldConstraints(),
            'attr' => [
                'placeholder' => 'Type first three letters of an existing attribute key for suggestions.',
            ],
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function createAttributeKeyFieldConstraints()
    {
        return [
            new NotBlank(),
            new Regex([
                'pattern' => '/^[a-z\-0-9_:]+$/',
                'message' => 'This field contains illegal characters. It should contain only lower case letters, ' .
                    'digits, numbers, underscores ("_"), hyphens ("-") and colons (":").',
            ]),
            new Callback([
                'methods' => [
                    function ($key, ExecutionContextInterface $context) {
                        /*if (!$this->isUniqueKey($key)) {
                            $context->addViolation('Attribute key is already used');
                        }*/
                    },
                ],
                'groups' => [self::GROUP_UNIQUE_KEY],
            ]),
        ];
    }

}
