<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup;

use Generated\Shared\Transfer\SalesOrderThresholdLocalizedMessageTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\LocalizedMessagesType;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeInterface;

abstract class AbstractGlobalThresholdFormMapper
{
    /**
     * @var \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[]
     */
    protected $formExpanderPlugins;

    /**
     * @param \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[] $formExpanderPlugins
     */
    public function __construct(
        SalesOrderThresholdGuiToLocaleFacadeInterface $localeFacade,
        array $formExpanderPlugins
    ) {
        $this->localeFacade = $localeFacade;
        $this->formExpanderPlugins = $formExpanderPlugins;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    protected function setLocalizedMessagesToSalesOrderThresholdTransfer(
        array $data,
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): SalesOrderThresholdTransfer {
        $localeCollection = $this->localeFacade->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $localizedMessage = (new SalesOrderThresholdLocalizedMessageTransfer())
                ->setLocaleCode($localeTransfer->getLocaleName())
                ->setMessage($data[$localeTransfer->getLocaleName()][LocalizedMessagesType::FIELD_MESSAGE]);

            $salesOrderThresholdTransfer->addLocalizedMessage($localizedMessage);
        }

        return $salesOrderThresholdTransfer;
    }
}
