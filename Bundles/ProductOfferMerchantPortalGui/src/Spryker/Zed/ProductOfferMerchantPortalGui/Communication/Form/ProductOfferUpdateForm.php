<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductOfferUpdateForm extends ProductOfferCreateForm
{
    protected const FIELD_PRODUCT_OFFER_REFERENCE = 'productOfferReference';

    protected const LABEL_PRODUCT_OFFER_REFERENCE = 'Offer reference';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'productOfferUpdate';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->addMerchantReferenceField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMerchantReferenceField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_PRODUCT_OFFER_REFERENCE, TextType::class, [
                'label' => static::LABEL_PRODUCT_OFFER_REFERENCE,
                'disabled' => true,
                'attr' => [
                    'read_only' => true,
                ],
            ]);

        return $this;
    }
}
