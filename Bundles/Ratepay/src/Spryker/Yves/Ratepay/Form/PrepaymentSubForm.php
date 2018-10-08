<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Ratepay\Form;

use Generated\Shared\Transfer\RatepayPaymentPrepaymentTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PrepaymentSubForm extends SubFormAbstract
{
    public const PAYMENT_METHOD = 'prepayment';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RatepayPaymentPrepaymentTransfer::class,
            SubFormInterface::OPTIONS_FIELD_NAME => [],
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
        return RatepayConstants::PAYMENT_METHOD_PREPAYMENT;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return RatepayConstants::PAYMENT_METHOD_PREPAYMENT;
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
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAcceptInquiry(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_ALLOW_CREDIT_INQUIRY,
            'checkbox',
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
