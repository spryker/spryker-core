<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;

class ReclamationType extends AbstractType
{
    public const TYPE_NAME = 'reclamation';
    public const FIELD_RECLAMATION = 'reclamation_id';
    public const OPTION_VALUE = 'option-value';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addReclamationField($builder, $options[static::OPTION_VALUE]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault(static::OPTION_VALUE, null);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string|null $value
     *
     * @return $this
     */
    protected function addReclamationField(FormBuilderInterface $builder, string $value = null)
    {
        $builder->add(static::FIELD_RECLAMATION, TextType::class, [
            'label' => 'Reclamation Id',
            'required' => false,
            'attr' => [
                'readonly' => true,
            ],
            'constraints' => [
                new GreaterThan(['value' => 0]),
            ],
            'data' => $value,
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return static::TYPE_NAME;
    }
}
