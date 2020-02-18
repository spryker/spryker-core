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
use Spryker\Zed\MerchantStock\Persistence\MerchantStockRepositoryInterface;

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
     * @var \Spryker\Zed\MerchantStock\Persistence\MerchantStockRepositoryInterface
     */
    protected $merchantStockRepository;

    /**
     * @param \Spryker\Zed\MerchantStock\Dependency\Facade\MerchantStockToStockFacadeInterface $stockFacade
     * @param \Spryker\Zed\MerchantStock\Persistence\MerchantStockEntityManagerInterface $merchantStockEntityManager
     * @param \Spryker\Zed\MerchantStock\Persistence\MerchantStockRepositoryInterface $merchantStockRepository
     */
    public function __construct(
        MerchantStockToStockFacadeInterface $stockFacade,
        MerchantStockEntityManagerInterface $merchantStockEntityManager,
        MerchantStockRepositoryInterface $merchantStockRepository
    ) {
        $this->stockFacade = $stockFacade;
        $this->merchantStockEntityManager = $merchantStockEntityManager;
        $this->merchantStockRepository = $merchantStockRepository;
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
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function updateMerchantStocksByMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        if ($merchantTransfer->getStockCollection()->count() < 1) {
            return $this->createMerchantResponseTransfer($merchantTransfer);
        }

        $existedMerchantStocks = $this->merchantStockRepository->getMerchantStocksByMerchant($merchantTransfer);

        $existedStockIdsForMerchant = [];

        foreach ($existedMerchantStocks as $merchantStock) {
            $existedStockIdsForMerchant[] = $merchantStock->getFkStock();
        }

        $this->createMerchantStocks($existedStockIdsForMerchant, $merchantTransfer);
        $this->deleteMerchantStocks($existedStockIdsForMerchant, $merchantTransfer);

        return $this->createMerchantResponseTransfer($merchantTransfer);
    }

    /**
     * @param int[] $existedStockIdsForMerchant
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    protected function createMerchantStocks(array $existedStockIdsForMerchant, MerchantTransfer $merchantTransfer): void
    {
        foreach ($merchantTransfer->getStockCollection() as $stockTransfer) {
            if (in_array($stockTransfer->getIdStock(), $existedStockIdsForMerchant, true)) {
                continue;
            }

            $this->merchantStockEntityManager->createMerchantStock($merchantTransfer, $stockTransfer);
        }
    }

    /**
     * @param int[] $existedStockIdsForMerchant
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    protected function deleteMerchantStocks(array $existedStockIdsForMerchant, MerchantTransfer $merchantTransfer): void
    {
        foreach ($merchantTransfer->getStockCollection() as $stockTransfer) {
            if ($key = array_search($stockTransfer->getIdStock(), $existedStockIdsForMerchant, true)) {
                unset($existedStockIdsForMerchant[$key]);
            }
        }

        foreach ($existedStockIdsForMerchant as $item) {
            $stockTransfer = (new StockTransfer())->setIdStock($item);
            $this->merchantStockEntityManager->deleteMerchantStock($merchantTransfer, $stockTransfer);
        }
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
        $merchantResponseTransfer = (new MerchantResponseTransfer())->setMerchant($merchantTransfer);

        if (!$message) {
            return $merchantResponseTransfer;
        }

        $merchantErrorTransfer = (new MerchantErrorTransfer())->setMessage($message);

        return $merchantResponseTransfer->setIsSuccess(false)->addError($merchantErrorTransfer);
    }
}
