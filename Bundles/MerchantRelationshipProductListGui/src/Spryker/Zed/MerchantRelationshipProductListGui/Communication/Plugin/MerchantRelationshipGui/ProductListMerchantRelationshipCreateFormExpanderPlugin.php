<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\Plugin\MerchantRelationshipGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipGuiExtension\Dependency\Plugin\MerchantRelationshipCreateFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\Communication\MerchantRelationshipProductListGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\MerchantRelationshipProductListGuiConfig getConfig()
 */
class ProductListMerchantRelationshipCreateFormExpanderPlugin extends AbstractPlugin implements MerchantRelationshipCreateFormExpanderPluginInterface
{
    /**
     * @uses \Spryker\Zed\MerchantRelationshipGui\Communication\Form\MerchantRelationshipCreateForm::OPTION_SELECTED_COMPANY
     */
    protected const OPTION_SELECTED_COMPANY = 'id_company';

    /**
     * {@inheritDoc}
     * - Does nothing if id_company option was not provided.
     * - Adds Product List multi-select field to merchant relationship create form.
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
        if (!$options[static::OPTION_SELECTED_COMPANY]) {
            return $builder;
        }

        return $this->getFactory()
            ->createProductListMerchantRelationshipFormExpander()
            ->expand($builder, $options);
    }
}
