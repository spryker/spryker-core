<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication\Form;

use Generated\Shared\Transfer\PriceProductVolumeItemTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataProvider\PriceVolumeCollectionDataProvider;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\PriceProductVolumeGui\Business\PriceProductVolumeGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductVolumeGui\Communication\PriceProductVolumeGuiCommunicationFactory getFactory()
 */
class PriceVolumeCollectionFormType extends AbstractType
{
    public const FIELD_VOLUMES = 'volumes';
    public const FIELD_ID_STORE = 'idStore';
    public const FIELD_ID_CURRENCY = 'idCurrency';
    public const FIELD_ID_PRODUCT_ABSTRACT = 'idProductAbstract';
    public const FIELD_ID_PRODUCT_CONCRETE = 'idProductConcrete';
    protected const VALIDATION_VOLUMES_GROUP = 'volumes_group';
    public const FIELD_NET_PRICE = 'net_price';
    public const FIELD_GROSS_PRICE = 'gross_price';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addVolumesField($builder, $options)
            ->addIdStoreField($builder)
            ->addIdCurrencyField($builder)
            ->addIdProductAbstractField($builder)
            ->addIdProductConcreteField($builder)
            ->addNetPriceField($builder, $options)
            ->addGrossPriceField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addVolumesField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_VOLUMES, CollectionType::class, [
            'entry_type' => PriceVolumeFormType::class,
            'entry_options' => [
                'label' => false,
                'data_class' => PriceProductVolumeItemTransfer::class,
                'constraints' => $this->getVolumesConstants(),
                PriceVolumeCollectionDataProvider::OPTION_CURRENCY_CODE => $options[PriceVolumeCollectionDataProvider::OPTION_CURRENCY_CODE],
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdStoreField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_STORE, HiddenType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Id store not defined.']),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCurrencyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_CURRENCY, HiddenType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Id currency not defined.']),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductAbstractField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT_ABSTRACT, HiddenType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Id product abstract not defined.']),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductConcreteField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT_CONCRETE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addNetPriceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_NET_PRICE, MoneyType::class, [
            'label' => 'Net Price',
            'currency' => $options[PriceVolumeCollectionDataProvider::OPTION_CURRENCY_CODE],
            'required' => false,
            'attr' => ['disabled' => 'disabled'],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addGrossPriceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_GROSS_PRICE, MoneyType::class, [
            'label' => 'Gross Price',
            'currency' => $options[PriceVolumeCollectionDataProvider::OPTION_CURRENCY_CODE],
            'required' => false,
            'attr' => ['disabled' => 'disabled'],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(PriceVolumeCollectionDataProvider::OPTION_CURRENCY_CODE);

        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                return [Constraint::DEFAULT_GROUP, static::VALIDATION_VOLUMES_GROUP];
            },
        ]);
    }

    /**
     * @return array
     */
    protected function getVolumesConstants()
    {
        $volumesConstants[] = new Callback([
            'callback' => function (PriceProductVolumeItemTransfer $priceProductVolumeItemTransfer, ExecutionContextInterface $context) {
                if (empty(array_filter($priceProductVolumeItemTransfer->toArray()))) {
                    return;
                }

                if (!$priceProductVolumeItemTransfer->getQuantity()) {
                    $context->addViolation('Quantity Should not be empty.');
                }

                if (!$priceProductVolumeItemTransfer->getNetPrice() && !$priceProductVolumeItemTransfer->getGrossPrice()) {
                    $context->addViolation(sprintf('Set up net or gross price for "quantity": %d.', $priceProductVolumeItemTransfer->getQuantity()));
                }
            },
            'groups' => [static::VALIDATION_VOLUMES_GROUP],
        ]);

        return $volumesConstants;
    }
}
