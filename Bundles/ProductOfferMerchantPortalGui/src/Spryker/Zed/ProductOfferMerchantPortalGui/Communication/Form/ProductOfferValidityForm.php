<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form;

use DateTime;
use Generated\Shared\Transfer\ProductOfferValidityTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Constraint\ValidFromRangeConstraint;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Constraint\ValidToRangeConstraint;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 */
class ProductOfferValidityForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_ID_PRODUCT_OFFER = 'idProductOffer';
    /**
     * @var string
     */
    protected const FIELD_VALID_FROM = 'validFrom';
    /**
     * @var string
     */
    protected const FIELD_VALID_TO = 'validTo';

    /**
     * @var string
     */
    protected const LABEL_VALID_FROM = 'Valid from';
    /**
     * @var string
     */
    protected const LABEL_VALID_TO = 'Valid to';

    /**
     * @var string
     */
    protected const PLACEHOLDER_VALID_FROM = 'From';
    /**
     * @var string
     */
    protected const PLACEHOLDER_VALID_TO = 'To';

    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'productOfferValidity';
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
            'data_class' => ProductOfferValidityTransfer::class,
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
        $this->addIdProductOfferField($builder)
            ->addValidFromField($builder)
            ->addValidToField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductOfferField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT_OFFER, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidFromField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_VALID_FROM, DateTimeType::class, [
            'required' => false,
            'label' => static::LABEL_VALID_FROM,
            'constraints' => [
                new ValidFromRangeConstraint(),
            ],
            'widget' => 'single_text',
            'attr' => [
                'placeholder' => static::PLACEHOLDER_VALID_FROM,
            ],
        ]);

        $builder->get(static::FIELD_VALID_FROM)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidToField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_VALID_TO, DateTimeType::class, [
            'required' => false,
            'label' => static::LABEL_VALID_TO,
            'constraints' => [
                new ValidToRangeConstraint(),
            ],
            'widget' => 'single_text',
            'attr' => [
                'placeholder' => static::PLACEHOLDER_VALID_TO,
            ],
        ]);

        $builder->get(static::FIELD_VALID_TO)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createDateTimeModelTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($value) {
                if ($value !== null) {
                    return new DateTime($value);
                }
            },
            function ($value) {
                if ($value instanceof DateTime) {
                    $value = $value->format(static::DATE_TIME_FORMAT);
                }

                return $value;
            }
        );
    }
}
