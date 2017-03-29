<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Mapper;

use Generated\Shared\Transfer\ProductApiTransfer;
use Propel\Runtime\Collection\ArrayCollection;
use Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface;

class TransferMapper implements TransferMapperInterface
{

    /**
     * @var \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface
     */
    protected $apiQueryContainer;

    /**
     * @param \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface $apiQueryContainer
     */
    public function __construct(ProductApiToApiInterface $apiQueryContainer)
    {
        $this->apiQueryContainer = $apiQueryContainer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function toTransfer(array $data)
    {
        $productApiTransfer = new ProductApiTransfer();
        $productApiTransfer->fromArray($data, true);

        return $productApiTransfer;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer[]|\Propel\Runtime\Collection\ArrayCollection $productEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer[]
     */
    public function toTransferCollection(ArrayCollection $productEntityCollection)
    {
        $transferList = [];
        foreach ($productEntityCollection as $productData) {
            $transferList[] = $this->toTransfer($productData);
        }

        return $transferList;
    }

}
