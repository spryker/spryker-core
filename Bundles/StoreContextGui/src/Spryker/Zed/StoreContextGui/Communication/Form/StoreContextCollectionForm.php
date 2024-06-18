<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Communication\Form;

use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\StoreContextGui\Communication\StoreContextGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\StoreContextGui\StoreContextGuiConfig getConfig()
 */
class StoreContextCollectionForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FORM_STORE_CONTEXT = 'applicationContexts';

    /**
     * @var string
     */
    protected const OPTION_TIMEZONES = 'timezones';

    /**
     * @var string
     */
    protected const OPTION_APPLICATIONS = 'applications';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addStoreContextForm($builder, $options);
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
            static::OPTION_TIMEZONES,
            static::OPTION_APPLICATIONS,
        ]);

        $resolver->setDefaults([
            'is_rendered' => true,
            'required' => false,
            'data_class' => StoreApplicationContextCollectionTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function addStoreContextForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FORM_STORE_CONTEXT, CollectionType::class, [
            'entry_type' => $this->getFactory()->getStoreContextFormClass(),
            'entry_options' => [
                static::OPTION_TIMEZONES => $options[static::OPTION_TIMEZONES],
                static::OPTION_APPLICATIONS => $options[static::OPTION_APPLICATIONS],
            ],
            'label' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'prototype_name' => '__store_context__',
        ]);

        $builder->get(static::FORM_STORE_CONTEXT)->addModelTransformer(
            $this->getFactory()->createStoreContextCollectionDataTransformer(),
        );
    }
}
