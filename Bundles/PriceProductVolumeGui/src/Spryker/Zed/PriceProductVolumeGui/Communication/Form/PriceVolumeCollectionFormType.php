<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication\Form;

use Closure;
use Generated\Shared\Transfer\PriceProductVolumeItemTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataProvider\PriceVolumeCollectionDataProvider;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\PriceProductVolumeGui\Communication\PriceProductVolumeGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductVolumeGui\PriceProductVolumeGuiConfig getConfig()
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

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addVolumesField($builder, $options)
            ->addNetPriceField($builder, $options)
            ->addGrossPriceField($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(PriceVolumeCollectionDataProvider::OPTION_CURRENCY_CODE);
        $resolver->setRequired(PriceVolumeCollectionDataProvider::OPTION_DIVISOR);
        $resolver->setRequired(PriceVolumeCollectionDataProvider::OPTION_FRACTION_DIGITS);

        $resolver->setDefaults([
            'validation_groups' => function () {
                return [Constraint::DEFAULT_GROUP, static::VALIDATION_VOLUMES_GROUP];
            },
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addVolumesField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_VOLUMES, CollectionType::class, [
            'entry_type' => PriceVolumeFormType::class,
            'allow_extra_fields' => true,
            'entry_options' => [
                'label' => false,
                'data_class' => PriceProductVolumeItemTransfer::class,
                'constraints' => $this->getVolumesConstraints(),
                PriceVolumeCollectionDataProvider::OPTION_CURRENCY_CODE => $options[PriceVolumeCollectionDataProvider::OPTION_CURRENCY_CODE],
                PriceVolumeCollectionDataProvider::OPTION_DIVISOR => $options[PriceVolumeCollectionDataProvider::OPTION_DIVISOR],
                PriceVolumeCollectionDataProvider::OPTION_FRACTION_DIGITS => $options[PriceVolumeCollectionDataProvider::OPTION_FRACTION_DIGITS],
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addNetPriceField(FormBuilderInterface $builder, array $options): self
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
    protected function addGrossPriceField(FormBuilderInterface $builder, array $options): self
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
    protected function addPriceField(FormBuilderInterface $builder, array $options, string $name): self
    {
        $builder->add($name, MoneyType::class, [
            'label' => false,
            'required' => false,
            'divisor' => $options[PriceVolumeCollectionDataProvider::OPTION_DIVISOR],
            'scale' => $options[PriceVolumeCollectionDataProvider::OPTION_FRACTION_DIGITS],
            'currency' => $options[PriceVolumeCollectionDataProvider::OPTION_CURRENCY_CODE],
            'attr' => ['readonly' => 'readonly'],
        ]);

        return $this;
    }

    /**
     * @return array
     */
    protected function getVolumesConstraints(): array
    {
        $volumesConstraints = [];
        $volumesConstraints[] = new Callback([
            'callback' => $this->getVolumesConstraintsCallback(),
            'groups' => [static::VALIDATION_VOLUMES_GROUP],
        ]);

        return $volumesConstraints;
    }

    /**
     * @return \Closure
     */
    protected function getVolumesConstraintsCallback(): Closure
    {
        $savedPriceProductVolumeItemTransfers = [];

        return function (PriceProductVolumeItemTransfer $priceProductVolumeItemTransfer, ExecutionContextInterface $context) use (&$savedPriceProductVolumeItemTransfers) {
            if (empty(array_filter($priceProductVolumeItemTransfer->toArray()))) {
                return;
            }

            if ($priceProductVolumeItemTransfer->getQuantity() === null) {
                $context
                    ->buildViolation('Quantity should not be empty.')
                    ->atPath(PriceVolumeFormType::FIELD_QUANTITY)
                    ->addViolation();
            }

            if ($priceProductVolumeItemTransfer->getQuantity() <= PriceVolumeFormType::MINIMUM_QUANTITY && $priceProductVolumeItemTransfer->getQuantity() !== null) {
                $context
                    ->buildViolation('Quantity should be greater than 1.')
                    ->atPath(PriceVolumeFormType::FIELD_QUANTITY)
                    ->addViolation();
            }

            if ($priceProductVolumeItemTransfer->getNetPrice() === null && $priceProductVolumeItemTransfer->getGrossPrice() === null) {
                if ($priceProductVolumeItemTransfer->getQuantity() > PriceVolumeFormType::MINIMUM_QUANTITY) {
                    $context
                        ->buildViolation(sprintf('Set up net or gross price for "quantity": %d.', $priceProductVolumeItemTransfer->getQuantity()))
                        ->atPath(PriceVolumeFormType::FIELD_QUANTITY)
                        ->addViolation();
                }
            }

            foreach ($savedPriceProductVolumeItemTransfers as $savedPriceProductVolumeItemTransfer) {
                if ($priceProductVolumeItemTransfer->getQuantity() === $savedPriceProductVolumeItemTransfer->getQuantity()) {
                    $context
                        ->buildViolation(sprintf('Quantity "%d" already exists.', $priceProductVolumeItemTransfer->getQuantity()))
                        ->atPath(PriceVolumeFormType::FIELD_QUANTITY)
                        ->addViolation();
                }
            }

            $savedPriceProductVolumeItemTransfers[] = $priceProductVolumeItemTransfer;
        };
    }
}
