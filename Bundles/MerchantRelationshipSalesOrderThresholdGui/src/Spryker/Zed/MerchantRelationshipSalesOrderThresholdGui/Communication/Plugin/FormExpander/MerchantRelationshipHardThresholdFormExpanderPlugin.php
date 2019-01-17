<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Plugin\FormExpander;

use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\MerchantRelationshipSalesOrderThresholdGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig getConfig()
 */
class MerchantRelationshipHardThresholdFormExpanderPlugin extends AbstractPlugin implements SalesOrderThresholdFormExpanderPluginInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getThresholdName(): string
    {
        return 'Hard Threshold';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getThresholdKey(): string
    {
        return MerchantRelationshipSalesOrderThresholdGuiConfig::HARD_TYPE_STRATEGY;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getThresholdGroup(): string
    {
        return MerchantRelationshipSalesOrderThresholdGuiConfig::GROUP_HARD;
    }

    /**
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        return $builder;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     * @param array $data
     *
     * @return array
     */
    public function mapSalesOrderThresholdValueTransferToFormData(SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer, array $data): array
    {
        return $data;
    }

    /**
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer
     */
    public function mapFormDataToTransfer(array $data, SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer): SalesOrderThresholdValueTransfer
    {
        $salesOrderThresholdValueTransfer->setSalesOrderThresholdType(
            (new SalesOrderThresholdTypeTransfer())
                ->setKey($this->getThresholdKey())
                ->setThresholdGroup($this->getThresholdGroup())
        );

        return $salesOrderThresholdValueTransfer;
    }
}
