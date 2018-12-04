<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Symfony\Component\Form\FormBuilderInterface;

interface SalesOrderThresholdFormExpanderPluginInterface
{
    /**
     * Specification:
     *  - Returns the threshold name.
     *
     * @api
     *
     * @return string
     */
    public function getThresholdName(): string;

    /**
     * Specification:
     *  - Returns the threshold key.
     *
     * @api
     *
     * @return string
     */
    public function getThresholdKey(): string;

    /**
     * Specification:
     *  - Returns the threshold group.
     *
     * @api
     *
     * @return string
     */
    public function getThresholdGroup(): string;

    /**
     * Specification:
     *  - Expands the sales order threshold form.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface;

    /**
     * Specification:
     *  - Maps SalesOrderThresholdValueTransfer to form data array.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     * @param array $data
     *
     * @return array
     */
    public function mapSalesOrderThresholdValueTransferToFormData(SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer, array $data): array;

    /**
     * Specification:
     *  - Maps the form data array to SalesOrderThresholdValueTransfer.
     *
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer
     */
    public function mapFormDataToTransfer(array $data, SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer): SalesOrderThresholdValueTransfer;
}
