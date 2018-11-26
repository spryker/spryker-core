<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Symfony\Component\Form\FormBuilderInterface;

interface SalesOrderThresholdFormExpanderPluginInterface
{
    /**
     * Specification:
     *  - TODO.
     *
     * @api
     *
     * @return string
     */
    public function getThresholdName(): string;

    /**
     * Specification:
     *  - TODO.
     *
     * @api
     *
     * @return string
     */
    public function getThresholdKey(): string;

    /**
     * Specification:
     *  - TODO.
     *
     * @api
     *
     * @return string
     */
    public function getThresholdGroup(): string;

    /**
     * Specification:
     *  - TODO.
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
     *  - TODO.
     *
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return array
     */
    public function getData(array $data, SalesOrderThresholdTransfer $salesOrderThresholdTransfer): array;

    /**
     * Specification:
     *  - TODO.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function mapData(SalesOrderThresholdTransfer $salesOrderThresholdTransfer, array $data): SalesOrderThresholdTransfer;
}
