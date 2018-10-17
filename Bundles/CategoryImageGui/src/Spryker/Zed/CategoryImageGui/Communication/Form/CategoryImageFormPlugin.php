<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form;

use Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryFormPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CategoryImageGui\Communication\CategoryImageGuiCommunicationFactory getFactory()
 */
class CategoryImageFormPlugin extends AbstractPlugin implements CategoryFormPluginInterface
{
    public const FORM_IMAGE_SET = 'image_set';

    public const IMAGES_FORM_NAME = 'imageSets';

    public const DEFAULT_LOCALE = 'default';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $this->addImageLocalizedForm($builder);
    }

    /**
     * @param string $localeName
     *
     * @return string
     */
    protected function getImagesFormName(string $localeName)
    {
        return static::IMAGES_FORM_NAME . '_' . $localeName;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImageLocalizedForm(FormBuilderInterface $builder)
    {
        $localeCollection = $this->getFactory()->createLocaleProvider()->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $name = $this->getImagesFormName($localeTransfer->getLocaleName());
            $this->addImageSetForm($builder, $name);
        }

        $defaultName = $this->getImagesFormName(static::DEFAULT_LOCALE);

        $this->addImageSetForm($builder, $defaultName);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $name
     *
     * @return $this
     */
    protected function addImageSetForm(FormBuilderInterface $builder, string $name)
    {
        $builder
            ->add($name, CollectionType::class, [
                'entry_type' => ImageSetForm::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__image_set_name__',
            ]);

        return $this;
    }
}
