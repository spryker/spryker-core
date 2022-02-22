<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ManualOrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\Traits\UniqueFlashMessagesTrait;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCalculationFacadeInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMessengerFacadeInterface;

class SummaryDataProvider implements FormDataProviderInterface
{
    use UniqueFlashMessagesTrait;

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCalculationFacadeInterface
     */
    protected $calculationFacade;

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCalculationFacadeInterface $calculationFacade
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        ManualOrderEntryGuiToCalculationFacadeInterface $calculationFacade,
        ManualOrderEntryGuiToMessengerFacadeInterface $messengerFacade
    ) {
        $this->calculationFacade = $calculationFacade;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData($transfer): QuoteTransfer
    {
        if ($transfer->getManualOrder() === null) {
            $transfer->setManualOrder(new ManualOrderTransfer());
        }

        $transfer = $this->calculationFacade->recalculateQuote($transfer);

        $this->uniqueFlashMessages();

        return $transfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return array
     */
    public function getOptions($transfer): array
    {
        return [
            'data_class' => QuoteTransfer::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ];
    }
}
