<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\MerchantRelationRequestMerchantPortalGuiCommunicationFactory getFactory()
 */
class CustomerForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_NAME = 'name';

    /**
     * @var string
     */
    protected const LABEL_NAME = 'Name';

    /**
     * @var string
     */
    protected const LABEL_EMAIL = 'Email';

    /**
     * @var string
     */
    protected const LABEL_PHONE = 'Phone';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomerTransfer::class,
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
        $this->addNameField($builder)
            ->addEmailField($builder)
            ->addPhoneField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $customerTransfer = $builder->getForm()->getData();

        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => static::LABEL_NAME,
            'required' => false,
            'disabled' => true,
            'mapped' => false,
            'data' => sprintf(
                '%s %s %s',
                $customerTransfer->getSalutation(),
                $customerTransfer->getFirstName(),
                $customerTransfer->getLastName(),
            ),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder->add(CustomerTransfer::EMAIL, TextType::class, [
            'label' => static::LABEL_EMAIL,
            'required' => false,
            'disabled' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addPhoneField(FormBuilderInterface $builder)
    {
        $builder->add(CustomerTransfer::PHONE, TextType::class, [
            'label' => static::LABEL_PHONE,
            'required' => false,
            'disabled' => true,
        ]);

        return $this;
    }
}
