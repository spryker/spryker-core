<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication\Expander;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ProductApprovalProductTableConfigurationExpander implements ProductApprovalProductTableConfigurationExpanderInterface
{
    /**
     * @uses \Spryker\Zed\ProductManagement\Communication\Table\ProductTable::COL_VARIANT_COUNT
     *
     * @var string
     */
    protected const COL_VARIANT_COUNT = 'variants';

    /**
     * @var string
     */
    protected const HEADER_APPROVAL_STATUS = 'Approval';

    /**
     * @var \Spryker\Zed\ProductApprovalGui\Communication\Expander\ArrayExpanderInterface
     */
    protected $arrayExpander;

    /**
     * @param \Spryker\Zed\ProductApprovalGui\Communication\Expander\ArrayExpanderInterface $arrayExpander
     */
    public function __construct(ArrayExpanderInterface $arrayExpander)
    {
        $this->arrayExpander = $arrayExpander;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandTableConfiguration(TableConfiguration $config): TableConfiguration
    {
        $header = $config->getHeader();
        $header = $this->arrayExpander->insertArrayItemAfterKey(
            $header,
            static::COL_VARIANT_COUNT,
            [ProductAbstractTransfer::APPROVAL_STATUS => static::HEADER_APPROVAL_STATUS],
        );
        $config->setHeader($header);
        $config->setRawColumns(array_merge($config->getRawColumns(), [ProductAbstractTransfer::APPROVAL_STATUS]));

        return $config;
    }
}
