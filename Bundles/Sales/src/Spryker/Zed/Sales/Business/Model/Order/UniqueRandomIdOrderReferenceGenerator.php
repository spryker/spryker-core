<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\IdGeneratorSettingsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Sales\Dependency\Facade\SalesToStoreInterface;
use Spryker\Zed\Sales\Dependency\Service\SalesToUtilUuidGeneratorInterface;
use Spryker\Zed\Sales\SalesConfig;

class UniqueRandomIdOrderReferenceGenerator implements OrderReferenceGeneratorInterface
{
    /**
     * @param \Spryker\Zed\Sales\Dependency\Service\SalesToUtilUuidGeneratorInterface $utilUuidGeneratorService
     * @param \Spryker\Zed\Sales\SalesConfig $salesConfig
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToStoreInterface $storeFacade
     */
    public function __construct(
        protected SalesToUtilUuidGeneratorInterface $utilUuidGeneratorService,
        protected SalesConfig $salesConfig,
        protected SalesToStoreInterface $storeFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function generateOrderReference(QuoteTransfer $quoteTransfer): string
    {
        if ($quoteTransfer->getOrderReference()) {
            return $quoteTransfer->getOrderReference();
        }

        $storeName = $quoteTransfer->getStore() ? $quoteTransfer->getStore()->getName() : $this->storeFacade->getCurrentStore()->getName();
        $sequenceNumberSettingTransfer = $this->salesConfig->getOrderReferenceDefaults($storeName);

        $idGeneratorSettingsTransfer = (new IdGeneratorSettingsTransfer())
            ->setAlphabet($this->salesConfig->getUniqueRandomIdOrderReferenceAlphabet())
            ->setSize($this->salesConfig->getUniqueRandomIdOrderReferenceSize())
            ->setSplitSeparator($this->salesConfig->getUniqueIdentifierSeparator())
            ->setSplitLength($this->salesConfig->getUniqueRandomIdOrderReferenceSplitLength());

        return $sequenceNumberSettingTransfer->getPrefix() . $this->utilUuidGeneratorService
            ->generateUniqueRandomId($idGeneratorSettingsTransfer);
    }
}
