<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\PriceProduct\PriceProductForm;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class ProductOfferCreateForm extends AbstractType
{
    public const OPTION_STORE_CHOICES = 'OPTION_STORE_CHOICES';

    protected const FIELD_MERCHANT_SKU = 'merchantSku';
    protected const FIELD_STORES = 'stores';
    protected const FIELD_IS_ACTIVE = 'isActive';
    protected const FIELD_PRODUCT_OFFER_STOCKS = 'productOfferStocks';
    protected const FIELD_PRODUCT_OFFER_VALIDITY = 'productOfferValidity';
    protected const FIELD_PRICES = 'prices';
    protected const BUTTON_CREATE = 'create';

    protected const LABEL_MERCHANT_SKU = 'Merchant SKU';
    protected const LABEL_STORES = 'Stores';
    protected const LABEL_IS_ACTIVE = 'Offer is online';
    protected const LABEL_PRODUCT_OFFER_STOCK = 'Stock';
    protected const LABEL_PRODUCT_OFFER_VALIDITY = 'Validity Dates';
    protected const LABEL_CREATE = 'Create';

    protected const PLACEHOLDER_MERCHANT_SKU = 'Enter SKU';
    protected const PLACEHOLDER_STORES = 'select.default.placeholder';

    protected const FIELD_MERCHANT_SKU_MAX_LENGTH = 255;
    protected const FIELD_STORES_MIN_COUNT = 1;

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'productOfferCreate';
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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
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
            ->addPricesSubform($builder)
            ->addProductOfferValiditySubform($builder);
    }

    /**
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
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
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
            ]
        );

        $builder->get(static::FIELD_STORES)
            ->addModelTransformer($this->getFactory()->createStoresTransformer());

        return $this;
    }

    /**
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
            ]
        );

        return $this;
    }

    /**
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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPricesSubform(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRICES, CollectionType::class, [
            'label' => false,
            'entry_type' => PriceProductForm::class,
            'allow_add' => true,
            'allow_delete' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormView $formViewCollection
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function finishView(FormView $formViewCollection, FormInterface $form, array $options): void
    {
        $pricesForm = $formViewCollection->children[static::FIELD_PRICES];
        $pricesFormTable = [];

        foreach ($pricesForm as $formView) {
            $priceProductTransfer = $this->getPriceProductTransfer($formView);
            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

            $formView->children[PriceProductForm::FIELD_NET_AMOUNT]->vars['label'] = $moneyValueTransfer->getCurrency()->getSymbol();
            $formView->children[PriceProductForm::FIELD_GROSS_AMOUNT]->vars['label'] = $moneyValueTransfer->getCurrency()->getSymbol();

            $storeName = $moneyValueTransfer->getStore()->getName();
            $priceTypeName = $priceProductTransfer->getPriceType()->getName();

            $pricesFormTable[$storeName]['GROSS'][$priceTypeName][] = $formView->children[PriceProductForm::FIELD_GROSS_AMOUNT];
            $pricesFormTable[$storeName]['NET'][$priceTypeName][] = $formView->children[PriceProductForm::FIELD_NET_AMOUNT];
        }

        $formViewCollection->vars['pricesFormTable'] = $pricesFormTable;
    }

    /**
     * @param \Symfony\Component\Form\FormView $formView
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function getPriceProductTransfer(FormView $formView): PriceProductTransfer
    {
        return $formView->vars['data'];
    }
}
