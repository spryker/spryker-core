<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyPayment\Form;

use Generated\Shared\Transfer\DummyPaymentTransfer;
use Spryker\Shared\DummyPayment\DummyPaymentConfig;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreditCardSubForm extends AbstractSubForm
{
    const PAYMENT_METHOD = 'credit_card';

    const FIELD_CARD_TYPE = 'card_type';
    const FIELD_CARD_NUMBER = 'card_number';
    const FIELD_NAME_ON_CARD = 'name_on_card';
    const FIELD_CARD_EXPIRES_MONTH = 'card_expires_month';
    const FIELD_CARD_EXPIRES_YEAR = 'card_expires_year';
    const FIELD_CARD_SECURITY_CODE = 'card_security_code';

    const OPTION_CARD_EXPIRES_CHOICES_MONTH = 'month choices';
    const OPTION_CARD_EXPIRES_CHOICES_YEAR = 'year choices';

    /**
     * @return string
     */
    public function getName()
    {
        return DummyPaymentConfig::PAYMENT_METHOD_CREDIT_CARD;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return DummyPaymentConfig::PAYMENT_METHOD_CREDIT_CARD;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return DummyPaymentConfig::PROVIDER_NAME . '/' . self::PAYMENT_METHOD;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DummyPaymentTransfer::class,
        ])->setRequired(self::OPTIONS_FIELD_NAME);
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
        $this->addCardType($builder)
             ->addCardNumber($builder)
             ->addNameOnCard($builder)
             ->addCardExpiresMonth($builder, $options)
             ->addCardExpiresYear($builder, $options)
             ->addCardSecurityCode($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addCardType(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_CARD_TYPE,
            'choice',
            [
                'choices' => ['Visa' => 'Visa', 'Master Card' => 'Master Card'],
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
     * @return \Spryker\Yves\DummyPayment\Form\CreditCardSubForm
     */
    protected function addCardNumber(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_CARD_NUMBER,
            'text',
            [
                'label' => false,
                'required' => true,
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
     * @return \Spryker\Yves\DummyPayment\Form\CreditCardSubForm
     */
    protected function addNameOnCard(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_NAME_ON_CARD,
            'text',
            [
                'label' => false,
                'required' => true,
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
    protected function addCardExpiresMonth(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_CARD_EXPIRES_MONTH,
            'choice',
            [
                'label' => false,
                'choices' => $options[self::OPTIONS_FIELD_NAME][self::OPTION_CARD_EXPIRES_CHOICES_MONTH],
                'required' => true,
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
    protected function addCardExpiresYear(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_CARD_EXPIRES_YEAR,
            'choice',
            [
                'label' => false,
                'choices' => $options[self::OPTIONS_FIELD_NAME][self::OPTION_CARD_EXPIRES_CHOICES_YEAR],
                'required' => true,
                'attr' => [
                    'placeholder' => 'Expires year',
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
     * @return \Spryker\Yves\DummyPayment\Form\CreditCardSubForm
     */
    protected function addCardSecurityCode(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_CARD_SECURITY_CODE,
            'text',
            [
                'label' => false,
                'required' => true,
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ]
        );

        return $this;
    }
}
