<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BundlesFormType extends AbstractType
{
    const FORM_TYPE_NAME = 'bundlesFormType';
    const BUNDLE_NAME_CHOICES = 'bundleNames';
    const EXCLUDED_BUNDLES = 'excludedBundles';
    const SHOW_INCOMING = 'showIncoming';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::BUNDLE_NAME_CHOICES);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return static::FORM_TYPE_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::EXCLUDED_BUNDLES, ChoiceType::class, [
            'expanded' => true,
            'multiple' => true,
            'choices' => $options[static::BUNDLE_NAME_CHOICES],
        ]);

        $builder->add(static::SHOW_INCOMING, CheckboxType::class, [
            'required' => false,
        ]);
    }
}
