<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\OrderSourceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\OrderSource\OrderSourceListType;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToManualOrderEntryFacadeInterface;

class OrderSourceListDataProvider implements FormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToManualOrderEntryFacadeInterface
     */
    protected $manualOrderEntryFacade;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToManualOrderEntryFacadeInterface $manualOrderEntryFacade
     */
    public function __construct(
        ManualOrderEntryGuiToManualOrderEntryFacadeInterface $manualOrderEntryFacade
    ) {
        $this->manualOrderEntryFacade = $manualOrderEntryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return array<string, mixed>
     */
    public function getOptions($transfer): array
    {
        return [
            'data_class' => QuoteTransfer::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            OrderSourceListType::OPTION_ORDER_SOURCE_ARRAY => $this->getOrderSourceList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData($transfer): QuoteTransfer
    {
        if ($transfer->getOrderSource() === null) {
            $transfer->setOrderSource(new OrderSourceTransfer());
        }

        return $transfer;
    }

    /**
     * @return array<string>
     */
    protected function getOrderSourceList(): array
    {
        $orderSourceTransfers = $this->manualOrderEntryFacade
            ->getAllOrderSources();

        $orderSourceList = [];

        foreach ($orderSourceTransfers as $orderSourceTransfer) {
            $orderSourceList[$orderSourceTransfer->getIdOrderSource()] = $orderSourceTransfer->getName();
        }

        return $orderSourceList;
    }
}
