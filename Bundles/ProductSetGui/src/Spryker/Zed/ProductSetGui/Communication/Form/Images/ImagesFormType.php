<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\Images;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 */
class ImagesFormType extends AbstractType
{
    public const FIELD_IMAGE_SET_COLLECTION_PREFIX = 'image_set_collection_';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addDefaultImageSetFormCollection($builder)
            ->addLocalizedImageSetFormCollection($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDefaultImageSetFormCollection(FormBuilderInterface $builder)
    {
        $builder->add(static::getImageSetFormName(), CollectionType::class, [
            'entry_type' => LocalizedProductImageSetFormType::class,
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
     *
     * @return $this
     */
    protected function addLocalizedImageSetFormCollection(FormBuilderInterface $builder)
    {
        $localeCollection = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        foreach ($localeCollection as $localeTransfer) {
            $builder->add(static::getImageSetFormName($localeTransfer->getLocaleName()), CollectionType::class, [
                'entry_type' => LocalizedProductImageSetFormType::class,
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
    public static function getImageSetFormName($localeName = 'default')
    {
        return static::FIELD_IMAGE_SET_COLLECTION_PREFIX . $localeName;
    }
}
