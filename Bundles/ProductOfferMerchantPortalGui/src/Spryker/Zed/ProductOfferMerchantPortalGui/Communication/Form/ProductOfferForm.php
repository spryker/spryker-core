<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class ProductOfferForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_STORE_CHOICES = 'OPTION_STORE_CHOICES';

    /**
     * @var string
     */
    protected const FIELD_MERCHANT_SKU = 'merchantSku';

    /**
     * @var string
     */
    protected const FIELD_STORES = 'stores';

    /**
     * @var string
     */
    protected const FIELD_IS_ACTIVE = 'isActive';

    /**
     * @var string
     */
    protected const FIELD_PRODUCT_OFFER_STOCKS = 'productOfferStocks';

    /**
     * @var string
     */
    protected const FIELD_PRODUCT_OFFER_VALIDITY = 'productOfferValidity';

    /**
     * @var string
     */
    protected const FIELD_PRODUCT_OFFER_PRICES = 'prices';

    /**
     * @var string
     */
    protected const BUTTON_CREATE = 'create';

    /**
     * @var string
     */
    protected const LABEL_MERCHANT_SKU = 'Merchant SKU';

    /**
     * @var string
     */
    protected const LABEL_STORES = 'Stores';

    /**
     * @var string
     */
    protected const LABEL_IS_ACTIVE = 'Offer is online';

    /**
     * @var string
     */
    protected const LABEL_PRODUCT_OFFER_STOCK = 'Stock';

    /**
     * @var string
     */
    protected const LABEL_PRODUCT_OFFER_VALIDITY = 'Validity Dates';

    /**
     * @var string
     */
    protected const LABEL_CREATE = 'Create';

    /**
     * @var string
     */
    protected const PLACEHOLDER_MERCHANT_SKU = 'Enter SKU';

    /**
     * @var string
     */
    protected const PLACEHOLDER_STORES = 'select.default.placeholder';

    /**
     * @var int
     */
    protected const FIELD_MERCHANT_SKU_MAX_LENGTH = 255;

    /**
     * @var int
     */
    protected const FIELD_STORES_MIN_COUNT = 1;

    /**
     * @var string
     */
    protected const BLOCK_PREFIX = 'productOffer';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductOfferTransfer::class,
        ]);

        $resolver->setRequired(static::OPTION_STORE_CHOICES);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCreateButton($builder)
            ->addMerchantSkuField($builder)
            ->addStoresField($builder, $options)
            ->addIsActiveField($builder)
            ->addProductOfferStockSubform($builder)
            ->addPrices($builder, $options)
            ->addProductOfferValiditySubform($builder);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCreateButton(FormBuilderInterface $builder)
    {
        $builder->add(static::BUTTON_CREATE, SubmitType::class, [
            'label' => static::LABEL_CREATE,
        ]);

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMerchantSkuField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_MERCHANT_SKU,
            TextType::class,
            [
                'required' => false,
                'label' => static::LABEL_MERCHANT_SKU,
                'attr' => [
                    'placeholder' => static::PLACEHOLDER_MERCHANT_SKU,
                ],
                'constraints' => [
                    new Length([
                        'max' => static::FIELD_MERCHANT_SKU_MAX_LENGTH,
                    ]),
                ],
            ],
        );

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addStoresField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_STORES,
            ChoiceType::class,
            [
                'choices' => $options[static::OPTION_STORE_CHOICES],
                'multiple' => true,
                'label' => static::LABEL_STORES,
                'required' => true,
                'empty_data' => [],
                'attr' => [
                    'placeholder' => static::PLACEHOLDER_STORES,
                ],
                'constraints' => [
                    new Count([
                        'min' => static::FIELD_STORES_MIN_COUNT,
                        'minMessage' => (new NotBlank())->message,
                    ]),
                ],
            ],
        );

        $builder->get(static::FIELD_STORES)
            ->addModelTransformer($this->getFactory()->createStoresTransformer());

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsActiveField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_IS_ACTIVE,
            CheckboxType::class,
            [
                'required' => false,
                'label' => static::LABEL_IS_ACTIVE,
            ],
        );

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductOfferStockSubform(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCT_OFFER_STOCKS, ProductOfferStockForm::class, [
            'label' => static::LABEL_PRODUCT_OFFER_STOCK,
        ]);

        $builder->get(static::FIELD_PRODUCT_OFFER_STOCKS)
            ->addModelTransformer($this->getFactory()->createProductOfferStockTransformer());

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductOfferValiditySubform(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCT_OFFER_VALIDITY, ProductOfferValidityForm::class, [
            'label' => static::LABEL_PRODUCT_OFFER_VALIDITY,
        ]);

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addPrices(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PRODUCT_OFFER_PRICES, HiddenType::class, [
            'required' => false,
            'label' => false,
        ]);

        $idProductOffer = $options['data']->getIdProductOffer();
        $priceProductOfferTransformer = $this->getFactory()->createPriceProductOfferTransformer($idProductOffer);

        $builder->get(static::FIELD_PRODUCT_OFFER_PRICES)
            ->addModelTransformer($priceProductOfferTransformer);

        return $this;
    }
}
