<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Business\Writer;

use Generated\Shared\Transfer\MerchantErrorTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\MerchantStock\Dependency\Facade\MerchantStockToStockFacadeInterface;
use Spryker\Zed\MerchantStock\Persistence\MerchantStockEntityManagerInterface;

class MerchantStockWriter implements MerchantStockWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantStock\Dependency\Facade\MerchantStockToStockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @var \Spryker\Zed\MerchantStock\Persistence\MerchantStockEntityManagerInterface
     */
    protected $merchantStockEntityManager;

    /**
     * @param \Spryker\Zed\MerchantStock\Dependency\Facade\MerchantStockToStockFacadeInterface $stockFacade
     * @param \Spryker\Zed\MerchantStock\Persistence\MerchantStockEntityManagerInterface $merchantStockEntityManager
     */
    public function __construct(
        MerchantStockToStockFacadeInterface $stockFacade,
        MerchantStockEntityManagerInterface $merchantStockEntityManager
    ) {
        $this->stockFacade = $stockFacade;
        $this->merchantStockEntityManager = $merchantStockEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function createMerchantStockByMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        $stockTransfer = (new StockTransfer())
            ->setName($this->getStockNameByMerchant($merchantTransfer))
            ->setIsActive(true);

        $stockTransfer = $this->stockFacade->createStock($stockTransfer)->getStock();
        $this->merchantStockEntityManager->createMerchantStock($merchantTransfer, $stockTransfer);
        $merchantTransfer->addStock($stockTransfer);

        return $this->createMerchantResponseTransfer($merchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return string
     */
    protected function getStockNameByMerchant(MerchantTransfer $merchantTransfer): string
    {
        return sprintf(
            '%s %s %d',
            $merchantTransfer->getName(),
            'Warehouse',
            $merchantTransfer->getStockCollection()->count() + 1
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    protected function createMerchantResponseTransfer(MerchantTransfer $merchantTransfer, string $message = ''): MerchantResponseTransfer
    {
        $merchantResponseTransfer = (new MerchantResponseTransfer())
            ->setMerchant($merchantTransfer)
            ->setIsSuccess(true);

        if (!$message) {
            return $merchantResponseTransfer;
        }

        $merchantErrorTransfer = (new MerchantErrorTransfer())->setMessage($message);

        return $merchantResponseTransfer->setIsSuccess(false)->addError($merchantErrorTransfer);
    }
}
