<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 */
class BundlesFormType extends AbstractType
{
    public const FORM_TYPE_NAME = 'bundlesFormType';
    public const BUNDLE_NAME_CHOICES = 'bundleNames';
    public const EXCLUDED_BUNDLES = 'excludedBundles';
    public const SHOW_INCOMING = 'showIncoming';

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
            'choices' => array_flip($options[static::BUNDLE_NAME_CHOICES]),
            'choices_as_values' => true,
        ]);

        $builder->add(static::SHOW_INCOMING, CheckboxType::class, [
            'required' => false,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return static::FORM_TYPE_NAME;
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
