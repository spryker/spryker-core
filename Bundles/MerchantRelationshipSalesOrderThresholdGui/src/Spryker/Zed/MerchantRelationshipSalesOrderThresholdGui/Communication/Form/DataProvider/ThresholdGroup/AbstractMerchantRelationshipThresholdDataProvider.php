<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\LocalizedMessagesType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\AbstractMerchantRelationshipThresholdType;

abstract class AbstractMerchantRelationshipThresholdDataProvider
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[]
     */
    protected $formExpanderPlugins;

    /**
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[] $formExpanderPlugins
     */
    public function __construct(array $formExpanderPlugins)
    {
        $this->formExpanderPlugins = $formExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     * @param array $data
     *
     * @return array
     */
    protected function expandFormData(MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer, array $data): array
    {
        foreach ($this->formExpanderPlugins as $formExpanderPlugin) {
            if ($formExpanderPlugin->getThresholdKey() !== $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()->getKey()) {
                continue;
            }

            $data[AbstractMerchantRelationshipThresholdType::FIELD_STRATEGY] = $formExpanderPlugin->getThresholdKey();
            $data = $formExpanderPlugin->mapSalesOrderThresholdValueTransferToFormData($merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue(), $data);
        }

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     * @param array $data
     *
     * @return array
     */
    protected function mapLocalizedMessages(MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer, array $data): array
    {
        foreach ($merchantRelationshipSalesOrderThresholdTransfer->getLocalizedMessages() as $localizedMessage) {
            $data[$localizedMessage->getLocaleCode()][LocalizedMessagesType::FIELD_MESSAGE] = $localizedMessage->getMessage();
        }

        return $data;
    }
}
