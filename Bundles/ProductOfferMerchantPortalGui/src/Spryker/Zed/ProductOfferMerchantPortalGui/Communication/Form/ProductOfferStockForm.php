<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 */
class ProductOfferStockForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_QUANTITY = 'quantity';
    /**
     * @var string
     */
    protected const FIELD_IS_NEVER_OUT_OF_STOCK = 'isNeverOutOfStock';

    /**
     * @var string
     */
    protected const LABEL_QUANTITY = 'Quantity';
    /**
     * @var string
     */
    protected const LABEL_IS_NEVER_OUT_OF_STOCK = 'Always in stock';

    /**
     * @var string
     */
    protected const PLACEHOLDER_QUANTITY = 'Enter quantity';

    /**
     * @var string
     */
    protected const DECIMAL_QUANTITY_VALIDATION_PATTERN = '/^\d{1,10}(\.\d{1,10})?$/';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'productOfferStock';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ProductOfferStockTransfer::class,
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
        $this->addQuantityField($builder)
            ->addIsNeverOutOfStockField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_QUANTITY, NumberType::class, [
            'label' => static::LABEL_QUANTITY,
            'required' => true,
            'attr' => [
                'placeholder' => static::PLACEHOLDER_QUANTITY,
            ],
            'constraints' => [
                new NotBlank(),
                new Regex([
                    'pattern' => static::DECIMAL_QUANTITY_VALIDATION_PATTERN,
                ]),
            ],
        ]);

        $builder->get(static::FIELD_QUANTITY)->addModelTransformer($this->getFactory()->createQuantityTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsNeverOutOfStockField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_NEVER_OUT_OF_STOCK, CheckboxType::class, [
            'label' => static::LABEL_IS_NEVER_OUT_OF_STOCK,
            'required' => false,
        ]);

        return $this;
    }
}
