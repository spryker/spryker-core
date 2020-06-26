<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Business\Reader;

use Spryker\Zed\MerchantProductSearch\Persistence\MerchantProductSearchRepositoryInterface;

class MerchantProductSearchReader implements MerchantProductSearchReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductSearch\Persistence\MerchantProductSearchRepositoryInterface
     */
    protected $merchantProductSearchRepository;

    /**
     * @param \Spryker\Zed\MerchantProductSearch\Persistence\MerchantProductSearchRepositoryInterface $merchantProductSearchRepository
     */
    public function __construct(MerchantProductSearchRepositoryInterface $merchantProductSearchRepository)
    {
        $this->merchantProductSearchRepository = $merchantProductSearchRepository;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantTransfer[]
     */
    public function getMerchantDataByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->merchantProductSearchRepository->getMerchantDataByProductAbstractIds($productAbstractIds);
    }
}
