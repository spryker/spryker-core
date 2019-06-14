<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdLocalizedMessageTransfer;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\LocalizedMessagesType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToLocaleFacadeInterface;

abstract class AbstractMerchantRelationshipThresholdFormMapper
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[]
     */
    protected $formExpanderPlugins;

    /**
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[] $formExpanderPlugins
     */
    public function __construct(
        MerchantRelationshipSalesOrderThresholdGuiToLocaleFacadeInterface $localeFacade,
        array $formExpanderPlugins
    ) {
        $this->localeFacade = $localeFacade;
        $this->formExpanderPlugins = $formExpanderPlugins;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    protected function setLocalizedMessagesToSalesOrderThresholdTransfer(
        array $data,
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        $localeCollection = $this->localeFacade->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $localizedMessage = (new SalesOrderThresholdLocalizedMessageTransfer())
                ->setLocaleCode($localeTransfer->getLocaleName())
                ->setMessage($data[$localeTransfer->getLocaleName()][LocalizedMessagesType::FIELD_MESSAGE]);

            $merchantRelationshipSalesOrderThresholdTransfer->addLocalizedMessage($localizedMessage);
        }

        return $merchantRelationshipSalesOrderThresholdTransfer;
    }
}
