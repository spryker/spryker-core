<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Version;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Required;

class CmsVersionFormType extends AbstractType
{
    const OPTION_VERSION_NAME_CHOICES = 'version_choices';
    const CMS_VERSION = 'cms_version';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addVersionNameField($builder, $options[static::OPTION_VERSION_NAME_CHOICES]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_VERSION_NAME_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addVersionNameField(FormBuilderInterface $builder, array $choices)
    {
        $attr = [];

        if (empty($choices)) {
            $attr['disabled'] = 'disabled';
        }

        $builder->add('version', ChoiceType::class, [
            'label' => false,
            'choices' => $choices,
            'required' => true,
            'constraints' => [
                new Required(),
                new NotNull(),
            ],
            'attr' => $attr,
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::CMS_VERSION;
    }
}
