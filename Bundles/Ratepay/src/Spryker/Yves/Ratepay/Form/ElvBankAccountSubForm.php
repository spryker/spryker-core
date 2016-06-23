<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Ratepay\Form;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ElvBankAccountSubForm extends SubFormAbstract implements SubFormInterface
{

    const PAYMENT_METHOD = 'elv';
    const SECTION_NAME = 'bankAccount';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults([
            'data_class' => RatepayPaymentElvTransfer::class,
            SubFormInterface::OPTIONS_FIELD_NAME => [],
        ]);
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return RatepayConstants::PAYMENT_METHOD_ELV . '_' . static::SECTION_NAME;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return RatepayConstants::PROVIDER_NAME . '_' . static::PAYMENT_METHOD . '_' . static::SECTION_NAME;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return RatepayConstants::PROVIDER_NAME . '/' . static::PAYMENT_METHOD . '_' . static::SECTION_NAME;
    }

}
