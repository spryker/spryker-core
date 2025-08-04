<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Form;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Controller\MerchantUserController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MultiFactorAuthMerchantPortal\MultiFactorAuthMerchantPortalConfig getConfig()
 * @method \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\MultiFactorAuthMerchantPortalCommunicationFactory getFactory()
 */
class TypeSelectionForm extends BaseMultiFactorAuthForm
{
    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'typeSelectionForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            MerchantUserController::TYPES => [],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->addSelectedMethodField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addSelectedMethodField(FormBuilderInterface $builder, array $options)
    {
        $mappedOptions = array_combine(
            array_map('ucfirst', $options[MerchantUserController::TYPES]),
            $options[MerchantUserController::TYPES],
        );

        $builder->add(MultiFactorAuthTransfer::TYPE, ChoiceType::class, [
            'choices' => $mappedOptions,
            'expanded' => true,
            'multiple' => false,
            'label' => false,
        ]);

        return $this;
    }
}
