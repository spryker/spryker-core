<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Form;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MerchantRelationRequestGui\MerchantRelationRequestGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationRequestGui\Communication\MerchantRelationRequestGuiCommunicationFactory getFactory()
 */
class ApproveMerchantRelationRequestForm extends AbstractType
{
    /**
     * @var string
     */
    protected const BUTTON_APPROVE = 'approve';

    /**
     * @var string
     */
    protected const LABEL_BUTTON_APPROVE = 'Confirm approval';

    /**
     * @var string
     */
    protected const LABEL_IS_SPLIT_ENABLED = 'Create a separate merchant relation per each business unit';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MerchantRelationRequestTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addApproveSubmitButton($builder)
            ->addIsSplitEnabledField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addApproveSubmitButton(FormBuilderInterface $builder)
    {
        $builder->add(static::BUTTON_APPROVE, SubmitType::class, [
            'label' => static::LABEL_BUTTON_APPROVE,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addIsSplitEnabledField(FormBuilderInterface $builder)
    {
        $builder->add(MerchantRelationRequestTransfer::IS_SPLIT_ENABLED, CheckboxType::class, [
            'label' => static::LABEL_IS_SPLIT_ENABLED,
            'required' => false,
        ]);

        return $this;
    }
}
