<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Payolution\Form;

use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Spryker\Shared\Payolution\PayolutionConstants;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Zed\Payolution\PayolutionConfig;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstallmentSubForm extends AbstractPayolutionSubForm
{
    const PAYMENT_PROVIDER = PayolutionConstants::PAYOLUTION;
    const PAYMENT_METHOD = 'installment';
    const FIELD_INSTALLMENT_PAYMENT_DETAIL_INDEX = 'installment_payment_detail_index';
    const FIELD_BANK_ACCOUNT_HOLDER = 'bank_account_holder';
    const FIELD_BANK_ACCOUNT_IBAN = 'bank_account_iban';
    const FIELD_BANK_ACCOUNT_BIC = 'bank_account_bic';

    const OPTION_INSTALLMENT_PAYMENT_DETAIL = 'installment_payment_detail';

    /**
     * @return string
     */
    public function getName()
    {
        return PayolutionConfig::PAYMENT_METHOD_INSTALLMENT;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return PayolutionConfig::PAYMENT_METHOD_INSTALLMENT;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return PayolutionConstants::PAYOLUTION . '/' . self::PAYMENT_METHOD;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PayolutionPaymentTransfer::class,
        ])->setRequired(SubFormInterface::OPTIONS_FIELD_NAME);
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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addInstallmentPaymentDetails($builder, $options)
             ->addDateOfBirth($builder)
             ->addBankAccountHolder($builder)
             ->addBankAccountIban($builder)
             ->addBankAccountBic($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function addInstallmentPaymentDetails(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_INSTALLMENT_PAYMENT_DETAIL_INDEX,
            'choice',
            [
                'choices' => $options['select_options'][self::OPTION_INSTALLMENT_PAYMENT_DETAIL],
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
     * @return \Spryker\Yves\Payolution\Form\InstallmentSubForm
     */
    protected function addBankAccountHolder(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_BANK_ACCOUNT_HOLDER,
            'text',
            [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'payolution_installment.bank_account_holder',
                ],
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
    protected function addBankAccountIban(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_BANK_ACCOUNT_IBAN,
            'text',
            [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'payolution_installment.bank_account_iban',
                ],
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
    protected function addBankAccountBic(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_BANK_ACCOUNT_BIC,
            'text',
            [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'payolution_installment.bank_account_bic',
                ],
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ]
        );

        return $this;
    }
}
