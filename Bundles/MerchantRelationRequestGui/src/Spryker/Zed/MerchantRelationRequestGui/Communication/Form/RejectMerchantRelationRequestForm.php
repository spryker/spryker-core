<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantRelationRequestGui\MerchantRelationRequestGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationRequestGui\Communication\MerchantRelationRequestGuiCommunicationFactory getFactory()
 */
class RejectMerchantRelationRequestForm extends AbstractType
{
    /**
     * @var string
     */
    protected const BUTTON_REJECT = 'reject';

    /**
     * @var string
     */
    protected const LABEL_BUTTON_REJECT = 'Confirm reject';

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
