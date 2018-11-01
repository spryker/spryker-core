<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Storage;

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
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface $priceAbstractStorageReader
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface $priceConcreteStorageReader
     */
    public function __construct(PriceAbstractStorageReaderInterface $priceAbstractStorageReader, PriceConcreteStorageReaderInterface $priceConcreteStorageReader)
    {
        $this->priceAbstractStorageReader = $priceAbstractStorageReader;
        $this->priceConcreteStorageReader = $priceConcreteStorageReader;
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function resolvePriceProductConcrete(int $idProductConcrete, int $idProductAbstract): array
    {
        $priceProductTransfers = $this->priceConcreteStorageReader
            ->findPriceProductConcreteTransfers($idProductConcrete);

        if (!$priceProductTransfers) {
            return $this->priceAbstractStorageReader
                ->findPriceProductAbstractTransfers($idProductAbstract);
        }

        return $priceProductTransfers;
    }
}
