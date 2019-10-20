<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Form\SlotBlock;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\CmsSlotBlockGui\CmsSlotBlockGuiConfig getConfig()
 * @method \Spryker\Zed\CmsSlotBlockGui\Communication\CmsSlotBlockGuiCommunicationFactory getFactory()
 */
class CmsBlockChoiceForm extends AbstractType
{
    public const OPTION_CMS_BLOCKS = 'cms_blocks';
    public const OPTION_CMS_BLOCKS_STORES = 'cms_blocks_stores';
    public const OPTION_CMS_BLOCK_IDS_ASSIGNED_TO_SLOT = 'cms_block_ids_assigned_to_slot';

    protected const FIELD_CMS_BLOCKS = 'cmsBlocks';
    protected const PLACEHOLDER_CMS_BLOCKS = 'Select or type a block name to assign';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            static::OPTION_CMS_BLOCKS => false,
            static::OPTION_CMS_BLOCKS_STORES => false,
            static::OPTION_CMS_BLOCK_IDS_ASSIGNED_TO_SLOT => false,
        ]);

        $resolver->setAllowedTypes(static::OPTION_CMS_BLOCKS, 'array');
        $resolver->setAllowedTypes(static::OPTION_CMS_BLOCKS_STORES, 'array');
        $resolver->setAllowedTypes(static::OPTION_CMS_BLOCK_IDS_ASSIGNED_TO_SLOT, 'array');
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCmsBlocksField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCmsBlocksField(FormBuilderInterface $builder, array $options)
    {
        dump($options[static::OPTION_CMS_BLOCK_IDS_ASSIGNED_TO_SLOT]);
        $builder->add(static::FIELD_CMS_BLOCKS, ChoiceType::class, [
            'placeholder' => static::PLACEHOLDER_CMS_BLOCKS,
            'choices' => $options[static::OPTION_CMS_BLOCKS],
            'required' => false,
            'label' => false,
            'choice_value' => function (?CmsBlockTransfer $cmsBlockTransfer = null): string {
                return $cmsBlockTransfer ? $cmsBlockTransfer->getIdCmsBlock() : '';
            },
            'choice_label' => function (?CmsBlockTransfer $cmsBlockTransfer = null): string {
                return $cmsBlockTransfer ? $cmsBlockTransfer->getName() : '';
            },
            'choice_attr' => function (CmsBlockTransfer $cmsBlockTransfer) use ($options): array {
                return [
                    'data-is-active' => $cmsBlockTransfer->getIsActive(),
                    'data-valid-from' => $cmsBlockTransfer->getValidFrom(),
                    'data-valid-to' => $cmsBlockTransfer->getValidTo(),
                    'data-stores' => $options[static::OPTION_CMS_BLOCKS_STORES][$cmsBlockTransfer->getIdCmsBlock()],
                    'disabled' => isset($options[static::OPTION_CMS_BLOCK_IDS_ASSIGNED_TO_SLOT][$cmsBlockTransfer->getIdCmsBlock()]),
                ];
            },
        ]);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     *
     * @return string[]
     */
    protected function getStoreNames(array $storeTransfers): array
    {
        return array_map(function (StoreTransfer $storeTransfer): string {
            return $storeTransfer->getName();
        }, $storeTransfers);
    }
}
