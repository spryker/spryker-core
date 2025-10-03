<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig getConfig()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Communication\MerchantRegistrationRequestCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Business\MerchantRegistrationRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestRepositoryInterface getRepository()
 */
class RejectMerchantRegistrationRequestForm extends AbstractType
{
    /**
     * @var string
     */
    protected const BUTTON_REJECT = 'reject';

    /**
     * @var string
     */
    protected const LABEL_BUTTON_REJECT = 'Reject Request';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addRejectSubmitButton($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addRejectSubmitButton(FormBuilderInterface $builder)
    {
        $builder->add(static::BUTTON_REJECT, SubmitType::class, [
            'label' => static::LABEL_BUTTON_REJECT,
        ]);

        return $this;
    }
}
