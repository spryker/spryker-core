<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Form\Block;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
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

    protected const FIELD_CMS_BLOCKS = 'cmsBlocks';
    protected const FIELD_ADD = 'add';
    protected const PLACEHOLDER_CMS_BLOCKS = 'Select or type a block name to assign';
    protected const LABEL_FIELD_ADD = '+Add';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([static::OPTION_CMS_BLOCKS]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCmsBlocksField($builder, $options)
            ->addAddField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCmsBlocksField(FormBuilderInterface $builder, array $options)
    {
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
            'choice_attr' => function (CmsBlockTransfer $cmsBlockTransfer): array {
                return [
                    'data-is-active' => $cmsBlockTransfer->getIsActive(),
                    'data-valid-from' => $this->formatValidityDateTime($cmsBlockTransfer->getValidFrom()),
                    'data-valid-to' => $this->formatValidityDateTime($cmsBlockTransfer->getValidTo()),
                    'data-stores' => $cmsBlockTransfer->getStoreNames(),
                    'disabled' => $cmsBlockTransfer->getIsAssignedToSlotAndTemplate(),
                ];
            },
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_ADD, ButtonType::class, [
                'label' => static::LABEL_FIELD_ADD,
                'attr' => [
                    'class' => 'btn-back',
                ],
            ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'block-choice';
    }

    /**
     * @param string|null $dateTime
     *
     * @return string
     */
    protected function formatValidityDateTime(?string $dateTime): string
    {
        return $dateTime
            ? date('F d, Y H:i', strtotime($dateTime))
            : '-';
    }
}
