<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication\Form;

use Generated\Shared\Transfer\CurrencyTransfer;
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
    public const FIELD_NET_PRICE = 'net_price';
    public const FIELD_GROSS_PRICE = 'gross_price';
    protected const VALIDATION_VOLUMES_GROUP = 'volumes_group';
    protected const DEFAULT_SCALE = 2;

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
            'allow_extra_fields' => true,
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
        $this->addPriceField($builder, $options, static::FIELD_NET_PRICE);

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
        $this->addPriceField($builder, $options, static::FIELD_GROSS_PRICE);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     * @param string $name
     *
     * @return $this
     */
    protected function addPriceField(FormBuilderInterface $builder, array $options, string $name)
    {
        $currencyTransfer = $options[PriceVolumeCollectionDataProvider::OPTION_CURRENCY_TRANSFER];
        $builder->add($name, MoneyType::class, [
            'label' => false,
            'required' => false,
            'divisor' => $this->getDivisor($currencyTransfer),
            'scale' => $this->getFractionDigits($currencyTransfer),
            'currency' => $options[PriceVolumeCollectionDataProvider::OPTION_CURRENCY_CODE],
            'attr' => ['readonly' => 'readonly'],
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
        $resolver->setRequired(PriceVolumeCollectionDataProvider::OPTION_CURRENCY_TRANSFER);

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
        $savedPriceProductVolumeItemTransfers = [];

        $volumesConstants[] = new Callback([
            'callback' => function (PriceProductVolumeItemTransfer $priceProductVolumeItemTransfer, ExecutionContextInterface $context) use (&$savedPriceProductVolumeItemTransfers) {
                if (empty(array_filter($priceProductVolumeItemTransfer->toArray()))) {
                    return;
                }

                if (!$priceProductVolumeItemTransfer->getQuantity()) {
                    $context->addViolation('Quantity Should not be empty.');
                }

                if (!$priceProductVolumeItemTransfer->getNetPrice() && !$priceProductVolumeItemTransfer->getGrossPrice()) {
                    $context->addViolation(sprintf('Set up net or gross price for "quantity": %d.', $priceProductVolumeItemTransfer->getQuantity()));
                }

                foreach ($savedPriceProductVolumeItemTransfers as $savedPriceProductVolumeItemTransfer) {
                    if ($priceProductVolumeItemTransfer->getQuantity() == $savedPriceProductVolumeItemTransfer->getQuantity()) {
                        $context->addViolation(sprintf('Quantity "%d" is duplicate.', $priceProductVolumeItemTransfer->getQuantity()));
                    }
                }

                $savedPriceProductVolumeItemTransfers[] = $priceProductVolumeItemTransfer;
            },
            'groups' => [static::VALIDATION_VOLUMES_GROUP],
        ]);

        return $volumesConstants;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    protected function getDivisor(CurrencyTransfer $currencyTransfer): int
    {
        $fractionDigits = $currencyTransfer->getFractionDigits();

        $divisor = 1;
        if ($fractionDigits) {
            $divisor = pow(10, $fractionDigits);
        }

        return $divisor;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    protected function getFractionDigits(CurrencyTransfer $currencyTransfer): int
    {
        $fractionDigits = $currencyTransfer->getFractionDigits();

        if ($fractionDigits !== null) {
            return $fractionDigits;
        }

        return static::DEFAULT_SCALE;
    }
}
