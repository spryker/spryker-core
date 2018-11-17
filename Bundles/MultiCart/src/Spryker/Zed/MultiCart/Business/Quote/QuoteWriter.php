<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business\Quote;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MultiCart\Business\Activator\QuoteActivator;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface;
use Spryker\Zed\MultiCart\Persistence\MultiCartEntityManagerInterface;
use Spryker\Zed\MultiCart\Persistence\MultiCartRepositoryInterface;

class QuoteWriter implements QuoteWriterInterface
{
    /**
     * @var \Spryker\Zed\MultiCart\Persistence\MultiCartRepositoryInterface
     */
    protected $multiCartRepository;

    /**
     * @var \Spryker\Zed\MultiCart\Persistence\MultiCartEntityManagerInterface
     */
    protected $multiCartEntityManager;

    /**
     * @var \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\MultiCart\Persistence\MultiCartRepositoryInterface $multiCartRepository
     * @param \Spryker\Zed\MultiCart\Persistence\MultiCartEntityManagerInterface $multiCartEntityManager
     * @param \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        MultiCartRepositoryInterface $multiCartRepository,
        MultiCartEntityManagerInterface $multiCartEntityManager,
        MultiCartToMessengerFacadeInterface $messengerFacade
    ) {
        $this->multiCartRepository = $multiCartRepository;
        $this->multiCartEntityManager = $multiCartEntityManager;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param string $customerReference
     *
     * @return void
     */
    public function initDefaultCustomerQuote(string $customerReference): void
    {
        $customerQuoteData = $this->multiCartRepository->findCustomerQuoteData($customerReference);
        if (!$customerQuoteData) {
            return;
        }

        if (!$this->hasActiveQuote($customerQuoteData)) {
            $quoteToActivateData = reset($customerQuoteData);
            $this->multiCartEntityManager->setDefaultQuote($quoteToActivateData[QuoteTransfer::ID_QUOTE]);
            $this->addSuccessMessage($quoteToActivateData[QuoteTransfer::NAME]);
        }
    }

    /**
     * @param array $customerQuoteData
     *
     * @return bool
     */
    protected function hasActiveQuote(array $customerQuoteData): bool
    {
        $numberOfCustomerQuoteDataRows = count($customerQuoteData);
        for ($i = 0; $i < $numberOfCustomerQuoteDataRows; $i++) {
            if ($customerQuoteData[$i][QuoteTransfer::IS_DEFAULT]) {
                return $customerQuoteData[$i][QuoteTransfer::ID_QUOTE];
            }
        }

        return false;
    }

    /**
     * @param string $quoteName
     *
     * @return void
     */
    protected function addSuccessMessage(string $quoteName): void
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer
            ->setValue(QuoteActivator::MULTI_CART_SET_DEFAULT_SUCCESS)
            ->setParameters(['%quote%' => $quoteName]);

        $this->messengerFacade->addInfoMessage($messageTransfer);
    }
}
