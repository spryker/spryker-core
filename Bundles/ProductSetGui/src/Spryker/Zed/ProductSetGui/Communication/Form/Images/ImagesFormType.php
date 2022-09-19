<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\Images;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSetGui\ProductSetGuiConfig getConfig()
 */
class ImagesFormType extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_IMAGE_SET_COLLECTION_PREFIX = 'image_set_collection_';

    /**
     * @var string
     */
    protected const OPTION_LOCALE = 'locale';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addDefaultImageSetFormCollection($builder, $options)
            ->addLocalizedImageSetFormCollection($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            static::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addDefaultImageSetFormCollection(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::getImageSetFormName(), CollectionType::class, [
            'entry_type' => LocalizedProductImageSetFormType::class,
            'entry_options' => [
                'locale' => $options[static::OPTION_LOCALE],
            ],
            'label' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'prototype_name' => '__image_set_name__',
            'attr' => [
                'localeName' => null,
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addLocalizedImageSetFormCollection(FormBuilderInterface $builder, array $options)
    {
        $localeCollection = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        foreach ($localeCollection as $localeTransfer) {
            $builder->add(static::getImageSetFormName($localeTransfer->getLocaleName()), CollectionType::class, [
                'entry_type' => LocalizedProductImageSetFormType::class,
                'entry_options' => [
                    'locale' => $options[static::OPTION_LOCALE],
                ],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__image_set_name__',
                'attr' => [
                    'localeName' => $localeTransfer->getLocaleName(),
                ],
            ]);
        }

        return $this;
    }

    /**
     * @param string $localeName
     *
     * @return string
     */
    public static function getImageSetFormName($localeName = 'default'): string
    {
        return static::FIELD_IMAGE_SET_COLLECTION_PREFIX . $localeName;
    }
}
