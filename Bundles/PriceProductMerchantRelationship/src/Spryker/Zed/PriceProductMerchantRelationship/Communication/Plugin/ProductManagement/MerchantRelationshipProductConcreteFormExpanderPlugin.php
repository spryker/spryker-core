<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Communication\Plugin\ProductManagement;

use Spryker\Shared\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductMerchantRelationship\Communication\Form\MerchantRelationshipPriceDimensionForm;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Communication\PriceProductMerchantRelationshipCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Business\PriceProductMerchantRelationshipFacadeInterface getFacade()
 */
class MerchantRelationshipProductConcreteFormExpanderPlugin extends AbstractPlugin implements ProductConcreteFormExpanderPluginInterface
{
    /**
     * @uses \Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd
     */
    protected const FORM_PRICE_DIMENSION = 'price_dimension';

    /**
     * {@inheritdoc}
     *  - Adds sub-form with merchant relationship dimension dropdown.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $options = $this->getFactory()
            ->createMerchantPriceDimensionFormDataProvider()
            ->getOptions();

        $builder->get(static::FORM_PRICE_DIMENSION)->add(
            PriceProductMerchantRelationshipConfig::PRICE_DIMENSION_MERCHANT_RELATIONSHIP,
            MerchantRelationshipPriceDimensionForm::class,
            $options
        );

        return $builder;
    }
}
