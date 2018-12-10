<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Storage;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductInterface;
use Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToPriceProductServiceInterface;

class PriceConcreteResolver implements PriceConcreteResolverInterface
{
    /**
     * @var \Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface
     */
    protected $priceAbstractStorageReader;

    /**
     * @var \Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface
     */
    protected $priceConcreteStorageReader;

    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToPriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductInterface
     */
    protected $priceProductClient;

    /**
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface $priceAbstractStorageReader
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface $priceConcreteStorageReader
     * @param \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToPriceProductServiceInterface $priceProductService
     * @param \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductInterface $priceProductClient
     */
    public function __construct(
        PriceAbstractStorageReaderInterface $priceAbstractStorageReader,
        PriceConcreteStorageReaderInterface $priceConcreteStorageReader,
        PriceProductStorageToPriceProductServiceInterface $priceProductService
        PriceProductStorageToPriceProductInterface $priceProductClient
    ) {
        $this->priceAbstractStorageReader = $priceAbstractStorageReader;
        $this->priceConcreteStorageReader = $priceConcreteStorageReader;
        $this->priceProductService = $priceProductService;
        $this->priceProductClient = $priceProductClient;
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function resolvePriceProductConcrete(int $idProductConcrete, int $idProductAbstract): array
    {
        $concretePriceProductTransfers = $this->priceConcreteStorageReader
            ->findPriceProductConcreteTransfers($idProductConcrete);

        $abstractPriceProductTransfers = $this->priceAbstractStorageReader
                ->findPriceProductAbstractTransfers($idProductAbstract);

        return $this->priceProductService->mergeConcreteAndAbstractPrices(
            $abstractPriceProductTransfers,
            $concretePriceProductTransfers
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveCurrentProductPriceTransfer(PriceProductFilterTransfer $priceProductFilterTransfer): CurrentProductPriceTransfer
    {
        $priceProductFilterTransfer->requireIdProduct();
        $priceProductFilterTransfer->requireIdProductAbstract();
        $priceProductFilterTransfer->requireQuantity();

        $priceProductTransfers = $this->resolvePriceProductConcrete(
            $priceProductFilterTransfer->getIdProduct(),
            $priceProductFilterTransfer->getIdProductAbstract()
        );

        $currentProductPriceTransfer = $this->priceProductClient->resolveProductPriceTransferByPriceProductFilter(
            $priceProductTransfers,
            (new PriceProductFilterTransfer())
                ->setQuantity($priceProductFilterTransfer->getQuantity())
        );

        // In case no $priceProductTransfers are provided for price resolving, the quantity is not copied in the result but required in the further process
        if ($currentProductPriceTransfer->getQuantity() === null) {
            $currentProductPriceTransfer->setQuantity($priceProductFilterTransfer->getQuantity());
        }

        return $currentProductPriceTransfer;
    }
}
