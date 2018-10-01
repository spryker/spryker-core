<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Ratepay\Form;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstallmentSubForm extends SubFormAbstract
{
    public const PAYMENT_METHOD = 'installment';

    public const OPTION_DEBIT_PAY_TYPE = 'debit_pay_type';
    public const OPTION_CALCULATION_TYPE = 'installment_calculation_type';
    public const OPTION_MONTH_ALLOWED = 'interest_month';

    public const FIELD_INTEREST_RATE = 'interest_rate';
    public const FIELD_INTEREST_RATE_DEFAULT = 'interest_rate_default';
    public const FIELD_BANK_ACCOUNT_HOLDER = 'bank_account_holder';
    public const FIELD_BANK_ACCOUNT_BIC = 'bank_account_bic';
    public const FIELD_BANK_ACCOUNT_IBAN = 'bank_account_iban';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RatepayPaymentInstallmentTransfer::class,
            SubFormInterface::OPTIONS_FIELD_NAME => [],
            'validation_groups' => function (FormInterface $form) {

                if ($form->getParent()[PaymentTransfer::PAYMENT_SELECTION]->getData() != $this->getPropertyPath()) {
                    return;
                }
                $data = $form->getData();

                if ($data->getDebitPayType() == RatepayConstants::DEBIT_PAY_TYPE_DIRECT_DEBIT) {
                    return [$this->getPropertyPath(), RatepayConstants::DEBIT_PAY_TYPE_DIRECT_DEBIT];
                }

                return [$this->getPropertyPath()];
            },
        ]);
    }

    /**
     * @deprecated Use `configureOptions()` instead.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return RatepayConstants::PAYMENT_METHOD_INSTALLMENT;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return RatepayConstants::PAYMENT_METHOD_INSTALLMENT;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return RatepayConstants::PROVIDER_NAME . '/' . static::PAYMENT_METHOD;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this
            ->addDebitPayType($builder, $options)
            ->addCalculationType($builder, $options)
            ->addBankAccountBic($builder)
            ->addBankAccountIban($builder)
            ->addAllowedMonth($builder, $options)
            ->addInterestRate($builder)
            ->addInterestRateDefault($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function addDebitPayType($builder, array $options)
    {
        $builder->add(
            self::OPTION_DEBIT_PAY_TYPE,
            'choice',
            [
                'choices' => $options['select_options'][self::OPTION_DEBIT_PAY_TYPE],
                'label' => false,
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => false,
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function addCalculationType($builder, array $options)
    {
        $builder->add(
            self::OPTION_CALCULATION_TYPE,
            'choice',
            [
                'choices' => $options['select_options'][self::OPTION_CALCULATION_TYPE],
                'label' => false,
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => false,
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function addAllowedMonth($builder, array $options)
    {
        $builder->add(
            self::OPTION_MONTH_ALLOWED,
            'choice',
            [
                'choices' => $options['select_options'][self::OPTION_MONTH_ALLOWED],
                'label' => false,
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => false,
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addInterestRate($builder)
    {
        $builder->add(
            self::FIELD_INTEREST_RATE,
            'text',
            [
                'label' => false,
                'constraints' => [],
                'attr' => [],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addInterestRateDefault($builder)
    {
        $builder->add(
            self::FIELD_INTEREST_RATE_DEFAULT,
            'text',
            [
                'label' => false,
                'constraints' => [],
                'attr' => [
                    'readonly' => 'readonly',
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addBankAccountHolder($builder)
    {
        $builder->add(
            self::FIELD_BANK_ACCOUNT_HOLDER,
            'text',
            [
                'label' => false,
                'required' => false,
                'constraints' => [
                    $this->createNotBlankConstraint(RatepayConstants::DEBIT_PAY_TYPE_DIRECT_DEBIT),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addBankAccountBic($builder)
    {
        $builder->add(
            self::FIELD_BANK_ACCOUNT_BIC,
            'text',
            [
                'label' => false,
                'required' => false,
                'constraints' => [
                    $this->createNotBlankConstraint(RatepayConstants::DEBIT_PAY_TYPE_DIRECT_DEBIT),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addBankAccountIban($builder)
    {
        $builder->add(
            self::FIELD_BANK_ACCOUNT_IBAN,
            'text',
            [
                'label' => false,
                'required' => false,
                'constraints' => [
                    $this->createNotBlankConstraint(RatepayConstants::DEBIT_PAY_TYPE_DIRECT_DEBIT),
                ],
            ]
        );

        return $this;
    }
}
