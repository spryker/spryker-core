<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Form\SlotBlock;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\CmsSlotBlockGui\Communication\CmsSlotBlockGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsSlotBlockGui\CmsSlotBlockGuiConfig getConfig()
 * @method \Spryker\Zed\CmsSlotBlockGui\Business\CmsSlotBlockGuiFacadeInterface getFacade()
 */
class CmsSlotBlockCollectionForm extends AbstractType
{
    public const OPTION_TEMPLATE_CONDITIONS = 'template_conditions';

    protected const FIELD_CMS_SLOT_BLOCKS = 'cmsSlotBlocks';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CmsSlotBlockCollectionTransfer::class,
        ]);
        $resolver->setRequired([static::OPTION_TEMPLATE_CONDITIONS]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCmsSlotBlockField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCmsSlotBlockField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_CMS_SLOT_BLOCKS, CollectionType::class, [
            'entry_type' => CmsSlotBlockForm::class,
            'label' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                'label' => false,
                static::OPTION_TEMPLATE_CONDITIONS => $options[static::OPTION_TEMPLATE_CONDITIONS],
            ],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'slot_blocks';
    }
}
