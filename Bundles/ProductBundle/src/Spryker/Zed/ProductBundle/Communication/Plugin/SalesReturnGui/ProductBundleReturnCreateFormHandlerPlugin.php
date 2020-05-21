<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\SalesReturnGui;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin\ReturnCreateFormHandlerPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface getQueryContainer()
 */
class ProductBundleReturnCreateFormHandlerPlugin extends AbstractPlugin implements ReturnCreateFormHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ReturnCreateForm data with product bundles information.
     *
     * @api
     *
     * @param array $returnCreateFormData
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function expandData(array $returnCreateFormData, OrderTransfer $orderTransfer): array
    {
        return $this->getFactory()
            ->createProductBundleReturnCreateFormDataProvider()
            ->getData($returnCreateFormData, $orderTransfer);
    }

    /**
     * {@inheritDoc}
     * - Expands ReturnCreateForm with product bundle subforms.
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
        return $this->getFactory()
            ->createReturnCreateBundleForm()
            ->buildForm($builder, $options);
    }

    /**
     * {@inheritDoc}
     * - Adds submitted product bundle items to ReturnCreateRequestTransfer.
     *
     * @api
     *
     * @param array $returnCreateFormData
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    public function handle(array $returnCreateFormData, ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnCreateRequestTransfer
    {
        return $this->getFactory()
            ->createProductBundleReturnCreateFormHandler()
            ->handle($returnCreateFormData, $returnCreateRequestTransfer);
    }
}
