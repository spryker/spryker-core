<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipGui\Communication\Plugin\ProductManagement;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductMerchantRelationshipGui\Communication\Form\MerchantRelationshipPriceDimensionForm;
use Spryker\Zed\PriceProductMerchantRelationshipGui\PriceProductMerchantRelationshipGuiConfig;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipGui\Communication\PriceProductMerchantRelationshipGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipGui\PriceProductMerchantRelationshipGuiConfig getConfig()
 */
class MerchantRelationshipProductAbstractFormExpanderPlugin extends AbstractPlugin implements ProductAbstractFormExpanderPluginInterface
{
    /**
     * @uses \Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd::FORM_PRICE_DIMENSION
     */
    protected const FORM_PRICE_DIMENSION = 'price_dimension';

    /**
     * {@inheritDoc}
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
            PriceProductMerchantRelationshipGuiConfig::PRICE_DIMENSION_MERCHANT_RELATIONSHIP,
            MerchantRelationshipPriceDimensionForm::class,
            $options
        );

        return $builder;
    }
}
