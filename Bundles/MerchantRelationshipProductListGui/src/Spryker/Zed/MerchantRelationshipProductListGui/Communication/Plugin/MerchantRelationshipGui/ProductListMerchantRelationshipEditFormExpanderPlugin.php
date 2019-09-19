<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\Plugin\MerchantRelationshipGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipGuiExtension\Dependency\Plugin\MerchantRelationshipEditFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\Communication\MerchantRelationshipProductListGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\MerchantRelationshipProductListGuiConfig getConfig()
 */
class ProductListMerchantRelationshipEditFormExpanderPlugin extends AbstractPlugin implements MerchantRelationshipEditFormExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adds Product List multi-select field to merchant relation form.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function expand(FormBuilderInterface $builder, array $options): void
    {
        $this->getFactory()
            ->createProductListMerchantRelationshipEditFormExpander()
            ->expand($builder, $options);
    }
}
