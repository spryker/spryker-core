<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\MerchantProfileMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\MerchantProfileMerchantPortalGuiConfig getConfig()
 */
class MerchantProfileForm extends AbstractType
{
    protected const FIELD_BUSINESS_INFO_MERCHANT_PROFILE = 'businessInfoMerchantProfile';
    protected const FIELD_ONLINE_PROFILE_MERCHANT_PROFILE = 'onlineProfileMerchantProfile';
    protected const BUTTON_SAVE = 'save';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'merchantProfile';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addBusinessInfoMerchantProfileSubform($builder)
            ->addOnlineProfileMerchantProfileSubform($builder)
            ->addSaveButton($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addBusinessInfoMerchantProfileSubform(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BUSINESS_INFO_MERCHANT_PROFILE,
            BusinessInfoMerchantProfileForm::class,
            [
                'label' => false,
                'data' => $builder->getForm()->getData(),
                'data_class' => MerchantTransfer::class,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOnlineProfileMerchantProfileSubform(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ONLINE_PROFILE_MERCHANT_PROFILE,
            OnlineProfileMerchantProfileForm::class,
            [
                'label' => false,
                'data' => $builder->getForm()->getData(),
                'data_class' => MerchantTransfer::class,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSaveButton(FormBuilderInterface $builder)
    {
        $builder->add(static::BUTTON_SAVE, SubmitType::class, [
            'label' => 'Save',
        ]);

        return $this;
    }
}
