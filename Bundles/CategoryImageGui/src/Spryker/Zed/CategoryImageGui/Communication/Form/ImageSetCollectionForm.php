<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\CategoryImageGui\Communication\CategoryImageGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryImageGui\CategoryImageGuiConfig getConfig()
 */
class ImageSetCollectionForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_LOCALES = 'locales';

    /**
     * @var string
     */
    public const OPTION_LOCALE = 'locale';

    /**
     * @var string
     */
    public const OPTION_IS_RENDERED = 'is_rendered';

    /**
     * @var string
     */
    public const OPTION_REQUIRED = 'required';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addImageLocalizedForms($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            static::OPTION_LOCALES,
        ]);

        $resolver->setDefaults([
            static::OPTION_IS_RENDERED => true,
            static::OPTION_REQUIRED => false,
            static::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if ($options[static::OPTION_IS_RENDERED]) {
            $view->setRendered();
        }
        $view->vars = array_merge($view->vars, [
            static::OPTION_LOCALES => $options[static::OPTION_LOCALES],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addImageLocalizedForms(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['locales'] as $localeName) {
            $this->addImageSetForm($builder, $localeName, $options);
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $name
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function addImageSetForm(FormBuilderInterface $builder, string $name, array $options): void
    {
        $builder->add($name, CollectionType::class, [
                'entry_type' => ImageSetForm::class,
                'entry_options' => [
                    'locale' => $options[static::OPTION_LOCALE],
                ],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__image_set_name__',
            ]);
    }
}
