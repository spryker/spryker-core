<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyMarketplacePayment\Form;

use Generated\Shared\Transfer\DummyMarketplacePaymentTransfer;
use Spryker\Shared\DummyMarketplacePayment\DummyMarketplacePaymentConfig;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Yves\DummyMarketplacePayment\DummyMarketplacePaymentFactory getFactory()
 */
class InvoiceSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    public const FIELD_DATE_OF_BIRTH = 'dateOfBirth';

    protected const PAYMENT_METHOD = 'invoice';

    /**
     * @return string
     */
    public function getProviderName()
    {
        return DummyMarketplacePaymentConfig::PAYMENT_PROVIDER_NAME;
    }

    /**
     * @return string
     */
    protected function getTemplatePath()
    {
        return DummyMarketplacePaymentConfig::PAYMENT_PROVIDER_NAME . DIRECTORY_SEPARATOR . static::PAYMENT_METHOD;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return DummyMarketplacePaymentConfig::PAYMENT_METHOD_DUMMY_MARKETPLACE_PAYMENT_INVOICE;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return DummyMarketplacePaymentConfig::PAYMENT_METHOD_DUMMY_MARKETPLACE_PAYMENT_INVOICE;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DummyMarketplacePaymentTransfer::class,
        ])->setRequired(static::OPTIONS_FIELD_NAME);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addDateOfBirth($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDateOfBirth(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_DATE_OF_BIRTH,
            BirthdayType::class,
            [
                'label' => 'dummyPaymentInvoice.date_of_birth',
                'required' => true,
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'html5' => false,
                'input' => 'string',
                'attr' => [
                    'placeholder' => 'customer.birth_date',
                ],
                'constraints' => [
                    new NotBlank(['groups' => $this->getPropertyPath()]),
                    $this->getFactory()->createDateOfBirthValueConstraint(),
                ],
            ]
        );

        return $this;
    }
}
