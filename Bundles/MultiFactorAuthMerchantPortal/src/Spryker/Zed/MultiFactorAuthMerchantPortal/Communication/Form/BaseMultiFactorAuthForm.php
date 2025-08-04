<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Controller\MerchantUserController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MultiFactorAuthMerchantPortal\MultiFactorAuthMerchantPortalConfig getConfig()
 * @method \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\MultiFactorAuthMerchantPortalCommunicationFactory getFactory()
 */
class BaseMultiFactorAuthForm extends AbstractType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            MerchantUserController::IS_ACTIVATION => null,
            MerchantUserController::IS_DEACTIVATION => null,
            MerchantUserController::TYPE_TO_SET_UP => null,
            MerchantUserController::MODAL_FORM_SELECTOR_PARAMETER => null,
            MerchantUserController::MODAL_AJAX_FORM_SELECTOR_PARAMETER => null,
            MerchantUserController::MODAL_IS_LOGIN_PARAMETER => false,
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
        $this->addIsActivationHiddenField($builder, $options);
        $this->addIsDeactivationHiddenField($builder, $options);
        $this->addTypeToSetUpHiddenField($builder, $options);
        $this->addFormSelectorHiddenField($builder, $options);
        $this->addAjaxFormSelectorHiddenField($builder, $options);
        $this->addIsLoginHiddenField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIsActivationHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(MerchantUserController::IS_ACTIVATION, HiddenType::class, [
            'data' => $options[MerchantUserController::IS_ACTIVATION],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIsDeactivationHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(MerchantUserController::IS_DEACTIVATION, HiddenType::class, [
            'data' => $options[MerchantUserController::IS_DEACTIVATION],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addTypeToSetUpHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(MerchantUserController::TYPE_TO_SET_UP, HiddenType::class, [
            'data' => $options[MerchantUserController::TYPE_TO_SET_UP],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addFormSelectorHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(MerchantUserController::MODAL_FORM_SELECTOR_PARAMETER, HiddenType::class, [
            'data' => $options[MerchantUserController::MODAL_FORM_SELECTOR_PARAMETER],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addAjaxFormSelectorHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(MerchantUserController::MODAL_AJAX_FORM_SELECTOR_PARAMETER, HiddenType::class, [
            'data' => $options[MerchantUserController::MODAL_AJAX_FORM_SELECTOR_PARAMETER],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIsLoginHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(MerchantUserController::MODAL_IS_LOGIN_PARAMETER, HiddenType::class, [
            'data' => $options[MerchantUserController::MODAL_IS_LOGIN_PARAMETER],
        ]);

        return $this;
    }
}
