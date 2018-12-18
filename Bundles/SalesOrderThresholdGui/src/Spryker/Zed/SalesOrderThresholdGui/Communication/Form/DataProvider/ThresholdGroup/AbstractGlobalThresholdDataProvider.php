<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\LocalizedMessagesType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\AbstractGlobalThresholdType;

abstract class AbstractGlobalThresholdDataProvider
{
    /**
     * @var \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[]
     */
    protected $formExpanderPlugins;

    /**
     * @param \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[] $formExpanderPlugins
     */
    public function __construct(array $formExpanderPlugins)
    {
        $this->formExpanderPlugins = $formExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     * @param array $data
     *
     * @return array
     */
    protected function expandFormData(SalesOrderThresholdTransfer $salesOrderThresholdTransfer, array $data): array
    {
        foreach ($this->formExpanderPlugins as $formExpanderPlugin) {
            if ($formExpanderPlugin->getThresholdKey() !== $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()->getKey()) {
                continue;
            }

            $data[AbstractGlobalThresholdType::FIELD_STRATEGY] = $formExpanderPlugin->getThresholdKey();
            $data = $formExpanderPlugin->mapSalesOrderThresholdValueTransferToFormData($salesOrderThresholdTransfer->getSalesOrderThresholdValue(), $data);
        }

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     * @param array $data
     *
     * @return array
     */
    protected function getLocalizedMessages(SalesOrderThresholdTransfer $salesOrderThresholdTransfer, array $data): array
    {
        foreach ($salesOrderThresholdTransfer->getLocalizedMessages() as $localizedMessage) {
            $data[$localizedMessage->getLocaleCode()][LocalizedMessagesType::FIELD_MESSAGE] = $localizedMessage->getMessage();
        }

        return $data;
    }
}
