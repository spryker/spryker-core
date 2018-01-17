<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Payolution\Form;

use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Spryker\Shared\Payolution\PayolutionConfig;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InvoiceSubForm extends AbstractPayolutionSubForm
{
    const PAYMENT_METHOD = 'invoice';

    /**
     * @return string
     */
    public function getName()
    {
        return PayolutionConfig::PAYMENT_METHOD_INVOICE;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return PayolutionConfig::PAYMENT_METHOD_INVOICE;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return PayolutionConfig::PROVIDER_NAME . '/' . self::PAYMENT_METHOD;
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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addDateOfBirth($builder);
    }
}
