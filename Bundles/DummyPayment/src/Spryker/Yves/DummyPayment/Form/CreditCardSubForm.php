<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyPayment\Form;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\DummyPaymentTransfer;
use Spryker\Shared\DummyPayment\DummyPaymentConstants;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreditCardSubForm extends AbstractSubForm
{

    const PAYMENT_PROVIDER = DummyPaymentConstants::PROVIDER_NAME;
    const PAYMENT_METHOD = 'credit_card';

    const FIELD_CARD_TYPE = 'card_type';
    const FIELD_CARD_NUMBER = 'card_number';
    const FIELD_NAME_ON_CARD= 'name_on_card';
    const FIELD_CARD_EXPIRES_MONTH = 'card_expires_month';
    const FIELD_CARD_EXPIRES_YEAR = 'card_expires_year';
    const FIELD_CARD_SECURITY_CODE = 'card_security_code';

    /**
     * @return string
     */
    public function getName()
    {
        return self::PAYMENT_PROVIDER . '_' . self::PAYMENT_METHOD;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return PaymentTransfer::DUMMY_PAYMENT_CREDIT_CARD;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return DummyPaymentConstants::PROVIDER_NAME . '/' . self::PAYMENT_METHOD;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults([
            'data_class' => DummyPaymentTransfer::class,
        ])->setRequired(SubFormInterface::OPTIONS_FIELD_NAME);
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
             ->addCardExpiresMonth($builder)
             ->addCardExpiresYear($builder)
             ->addCardSecurityCode($builder)
        ;
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
                'empty_value' => false,
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
     *
     * @return \Spryker\Yves\DummyPayment\Form\CreditCardSubForm
     */
    protected function addCardExpiresMonth(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_CARD_EXPIRES_MONTH,
            'choice',
            [
                'label' => false,
                'choices' => $this->getMonthChoices(),
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
    protected function addCardExpiresYear(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_CARD_EXPIRES_YEAR,
            'choice',
            [
                'label' => false,
                'choices' => $this->getYearChoices(),
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

    /**
     * @return array
     */
    protected function getMonthChoices()
    {
        return [
            '01' => '01',
            '02' => '02',
            '03' => '03',
            '04' => '04',
            '05' => '05',
            '06' => '06',
            '07' => '07',
            '08' => '08',
            '09' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12',
        ];
    }

    /**
     * @return array
     */
    protected function getYearChoices()
    {
        $currentYear = date('Y');
        
        return [
            $currentYear => $currentYear,
            ++$currentYear => $currentYear,
            ++$currentYear => $currentYear,
            ++$currentYear => $currentYear,
            ++$currentYear => $currentYear,
        ];
    }

}
