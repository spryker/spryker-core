<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form;

use Spryker\Zed\CategoryImageGui\CategoryImageGuiConfig;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CategoryImageGui\Communication\CategoryImageGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryImageGui\CategoryImageGuiConfig getConfig()
 */
class ImageSetCollectionForm extends AbstractType
{
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
        foreach ($this->getLocaleNames() as $localeName) {
            $this->addImageSetForm($builder, $localeName);
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

    /**
     * @return string[]
     */
    protected function getLocaleNames(): array
    {
        $localeFacade = $this->getFactory()->getLocaleFacade();

        return array_merge(
            [CategoryImageGuiConfig::DEFAULT_LOCALE_NAME],
            $localeFacade->getAvailableLocales()
        );
    }
}
