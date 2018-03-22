<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\DataMapper;

use Generated\Shared\Transfer\ProductSetTransfer;
use Spryker\Zed\ProductSetGui\Communication\Form\ReorderProductSetsFormType;
use Symfony\Component\Form\FormInterface;

class ReorderFormDataToTransferMapper
{
    /**
     * @param \Symfony\Component\Form\FormInterface $reorderProductSetForm
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer[]
     */
    public function mapData(FormInterface $reorderProductSetForm)
    {
        $productSetTransfers = [];

        $data = $reorderProductSetForm->get(ReorderProductSetsFormType::FIELD_PRODUCT_SET_WEIGHTS)->getData();
        foreach ($data as $idProductSet => $weight) {
            $productSetTransfer = new ProductSetTransfer();
            $productSetTransfer
                ->setIdProductSet($idProductSet)
                ->setWeight($weight);

            $productSetTransfers[] = $productSetTransfer;
        }

        return $productSetTransfers;
    }
}
