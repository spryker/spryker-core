<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer;

class RelatedProductFormDataProvider
{
    /**
     * @param int|null $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer
     */
    public function getData($idProductLabel = null)
    {
        $relationsTransfer = new ProductLabelProductAbstractRelationsTransfer();
        $relationsTransfer->setIdProductLabel($idProductLabel);

        return $relationsTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }
}
