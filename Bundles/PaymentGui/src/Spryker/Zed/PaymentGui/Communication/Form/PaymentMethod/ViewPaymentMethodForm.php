<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentGui\Communication\Form\PaymentMethod;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\PaymentGui\Communication\Form\DataProvider\ViewPaymentMethodFormDataProvider;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\PaymentGui\Communication\PaymentGuiCommunicationFactory getFactory()
 */
class ViewPaymentMethodForm extends AbstractType
{
    public const FIELD_STORE_RELATION = 'storeRelation';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaymentMethodTransfer::class,
        ]);
        $resolver->setRequired([
            ViewPaymentMethodFormDataProvider::OPTION_STORE_RELATION_DISABLED,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addStoreRelationForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addStoreRelationForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_STORE_RELATION,
            $this->getFactory()->getStoreRelationFormTypePlugin()->getType(),
            [
                'label' => false,
                'required' => false,
                'disabled' => $options[ViewPaymentMethodFormDataProvider::OPTION_STORE_RELATION_DISABLED],
            ]
        );

        return $this;
    }
}
