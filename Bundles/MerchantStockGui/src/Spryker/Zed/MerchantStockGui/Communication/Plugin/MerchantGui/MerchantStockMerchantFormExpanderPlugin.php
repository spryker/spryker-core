<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStockGui\Communication\Plugin\MerchantGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantStockGui\Communication\MerchantStockGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantStockGui\MerchantStockGuiConfig getConfig()
 */
class MerchantStockMerchantFormExpanderPlugin extends AbstractPlugin implements MerchantFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands MerchantForm with form field for merchant warehouses.
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
        $merchantStockFormDataProvider = $this->getFactory()->createMerchantStockFormDataProvider();
        $merchantStockForm = $this->getFactory()->createMerchantStockForm();
        $merchantStockForm->buildForm($builder, $merchantStockFormDataProvider->getOptions($builder->getData()));

        return $builder;
    }
}
