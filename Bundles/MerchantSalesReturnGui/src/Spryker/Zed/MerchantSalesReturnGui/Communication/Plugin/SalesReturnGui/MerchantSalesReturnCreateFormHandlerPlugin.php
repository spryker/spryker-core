<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnGui\Communication\Plugin\SalesReturnGui;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin\ReturnCreateFormHandlerPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantSalesReturnGui\Communication\MerchantSalesReturnGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesReturnGui\MerchantSalesReturnGuiConfig getConfig()
 */
class MerchantSalesReturnCreateFormHandlerPlugin extends AbstractPlugin implements ReturnCreateFormHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `ReturnCreateForm` data with merchant product data.
     *
     * @api
     *
     * @param array<string, mixed> $returnCreateFormData
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<string, mixed>
     */
    public function expandData(array $returnCreateFormData, OrderTransfer $orderTransfer): array
    {
        return $this->getFactory()
            ->createMerchantSalesReturnCreateFormDataProvider()
            ->getData($returnCreateFormData, $orderTransfer);
    }

    /**
     * {@inheritDoc}
     * - Expands `ReturnCreateForm` with merchant product sub-forms.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<mixed>
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this->getFactory()
            ->createMerchantSalesReturnCreateForm()
            ->buildForm($builder, $options);

        return $builder;
    }

    /**
     * {@inheritDoc}
     * - Adds submitted merchant product items to `ReturnCreateRequestTransfer`.
     *
     * @api
     *
     * @param array<string, mixed> $returnCreateFormData
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    public function handle(array $returnCreateFormData, ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnCreateRequestTransfer
    {
        return $this->getFactory()
            ->createMerchantSalesReturnCreateFormHandler()
            ->handle($returnCreateFormData, $returnCreateRequestTransfer);
    }
}
