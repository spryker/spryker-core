<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use RuntimeException;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPreReorderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentBusinessFactory getBusinessFactory()
 */
class OrderAmendmentQuoteProcessFlowExpanderCartPreReorderPlugin extends AbstractPlugin implements CartPreReorderPluginInterface
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_SESSION
     *
     * @var string
     */
    protected const STORAGE_STRATEGY_SESSION = 'session';

    /**
     * {@inheritDoc}
     * - Does nothing if `CartReorderRequestTransfer.isAmendment` flag is not set.
     * - Throws `RuntimeException` if the `session` quote storage strategy is used.
     * - Requires `CartReorderTransfer.quote` to be set.
     * - Expands `CartReorderTransfer.quote.quoteProcessFlow` with the quote process flow name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function preReorder(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer {
        if (!$cartReorderRequestTransfer->getIsAmendment()) {
            return $cartReorderTransfer;
        }

        $this->assertQuoteStorageStrategy();

        $cartReorderTransfer->getQuoteOrFail()->setQuoteProcessFlow(
            $this->getConfig()->getDefaultOrderAmendmentQuoteProcessFlow(),
        );

        return $cartReorderTransfer;
    }

    /**
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function assertQuoteStorageStrategy(): void
    {
        if ($this->getBusinessFactory()->getQuoteFacade()->getStorageStrategy() === static::STORAGE_STRATEGY_SESSION) {
            throw new RuntimeException(
                'The session storage strategy is not supported for the order amendment process flow.',
            );
        }
    }
}
