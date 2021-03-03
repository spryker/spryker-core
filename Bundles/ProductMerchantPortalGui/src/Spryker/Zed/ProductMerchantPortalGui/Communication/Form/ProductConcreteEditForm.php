<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 */
class ProductConcreteEditForm extends AbstractType
{
    public const FIELD_PRODUCT_CONCRETE = 'productConcrete';
    public const FIELD_USE_ABSTRACT_PRODUCT_PRICES = 'useAbstractProductPrices';

    protected const BLOCK_PREFIX = 'productConcreteEdit';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addProductConcreteSubForm($builder)
            ->addUseAbstractProductPricesField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductConcreteSubForm(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCT_CONCRETE, ProductConcreteForm::class, [
            'constraints' => [
                $this->getFactory()->createProductConcreteOwnedByMerchantConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUseAbstractProductPricesField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_USE_ABSTRACT_PRODUCT_PRICES, CheckboxType::class);

        return $this;
    }
}
