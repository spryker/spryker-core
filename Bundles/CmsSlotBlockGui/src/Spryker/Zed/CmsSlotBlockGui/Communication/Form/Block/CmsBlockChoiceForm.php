<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Form\Block;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CmsSlotBlockGui\Business\CmsSlotBlockGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsSlotBlockGui\CmsSlotBlockGuiConfig getConfig()
 * @method \Spryker\Zed\CmsSlotBlockGui\Communication\CmsSlotBlockGuiCommunicationFactory getFactory()
 */
class CmsBlockChoiceForm extends AbstractType
{
    protected const FIELD_CMS_BLOCKS = 'cmsBlocks';
    protected const FIELD_ADD = 'add';
    protected const PLACEHOLDER_CMS_BLOCKS = 'Select or type a block name to assign';
    protected const LABEL_FIELD_ADD = '+Add';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCmsBlockField($builder)
            ->addAddField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCmsBlockField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CMS_BLOCKS, ChoiceType::class, [
            'placeholder' => static::PLACEHOLDER_CMS_BLOCKS,
            'choices' => [],
            'required' => false,
            'label' => false,
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
}
