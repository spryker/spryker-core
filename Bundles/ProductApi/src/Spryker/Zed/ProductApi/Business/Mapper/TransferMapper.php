<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Mapper;

use Generated\Shared\Transfer\ProductApiTransfer;

class TransferMapper implements TransferMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function toTransfer(array $data)
    {
        $productApiTransfer = new ProductApiTransfer();

        $data = $this->mapAttributes($data);
        $productApiTransfer->fromArray($data, true);

        return $productApiTransfer;
    }

    /**
     * @param array $productEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer[]
     */
    public function toTransferCollection(array $productEntityCollection)
    {
        $transferList = [];
        foreach ($productEntityCollection as $productData) {
            $transferList[] = $this->toTransfer($productData);
        }

        return $transferList;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function mapAttributes(array $data)
    {
        if (array_key_exists(ProductApiTransfer::ATTRIBUTES, $data)) {
            $jsonAttributes = trim($data[ProductApiTransfer::ATTRIBUTES]);
            if ($jsonAttributes) {
                $data[ProductApiTransfer::ATTRIBUTES] = json_decode($jsonAttributes, true); //TODO inject util encoding
            } else {
                $data[ProductApiTransfer::ATTRIBUTES] = null;
            }
        }

        return $data;
    }
}
