<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SprykForm extends AbstractType
{
    const SPRYK = 'spryk';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return \Symfony\Component\OptionsResolver\OptionsResolver|void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'spryk',
            'sprykDefinitions',
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
        $this->addSprykSelect($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function addSprykSelect(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $sprykDefinitions = $options['sprykDefinitions'];

        $choices = [];
        foreach ($sprykDefinitions as $spryk => $sprykDefinition) {
            $choices[$spryk] = $spryk;
        }

        $builder->add(static::SPRYK, ChoiceType::class, [
            'choices' => $choices,
        ]);

        return $builder;
    }
}
