<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CategoryImageGui\Communication\CategoryImageGuiCommunicationFactory getFactory()
 */
class ImageSetCollectionForm extends AbstractType
{
    public const FORM_IMAGE_SET = 'image_set';
    public const CATEGORY_DEFAULT_LOCALE = 'default';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addImageLocalizedForms($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImageLocalizedForms(FormBuilderInterface $builder)
    {
        $localeCollection = $this->getFactory()->createLocaleProvider()->getLocaleCollection(true);

        foreach ($localeCollection as $localeTransfer) {
            $this->addImageSetForm($builder, $localeTransfer->getLocaleName());
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $name
     *
     * @return void
     */
    protected function addImageSetForm(FormBuilderInterface $builder, string $name): void
    {
        $builder->add($name, CollectionType::class, [
                'entry_type' => ImageSetForm::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__image_set_name__',
            ]);
    }
}
