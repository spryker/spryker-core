<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocaleGui\Communication\FormExpander;

use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\UserLocaleGui\Communication\UserLocaleGuiCommunicationFactory getFactory()
 */
class UserLocaleFormExpander extends AbstractType
{
    protected const FIELD_FK_LOCALE = 'fk_locale';

    public const OPTIONS_LOCALE = 'OPTIONS_LOCALE';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setRequired(static::OPTIONS_LOCALE);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addLocaleField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addLocaleField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FIELD_FK_LOCALE, SelectType::class, [
            'label' => 'Interface language',
            'choices' => $options[static::OPTIONS_LOCALE],
            'required' => true,
        ]);
    }
}
