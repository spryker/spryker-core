<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\StoreContextGui\StoreContextGuiConfig getConfig()
 * @method \Spryker\Zed\StoreContextGui\Communication\StoreContextGuiCommunicationFactory getFactory()
 */
class StoreTimezoneForm extends AbstractType
{
    /**
     * @var string
     */
    protected const OPTION_TIMEZONE_CHOICES = 'timezone_list';

    /**
     * @var string
     */
    protected const TIMEZONE = 'timezone';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_TIMEZONE_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addTimezoneFied($builder, $options[static::OPTION_TIMEZONE_CHOICES]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     * @param array<string> $choices
     *
     * @return $this
     */
    protected function addTimezoneFied(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::TIMEZONE, Select2ComboBoxType::class, [
            'multiple' => false,
            'choices' => $choices,
            'constraints' => $this->getTimezoneFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getTimezoneFieldConstraints(): array
    {
        return [
            new NotBlank(),
        ];
    }
}
