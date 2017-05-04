<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection;

interface LocalizedAttributesCollectionReaderInterface
{

    /**
     * @param int $idProductLabel
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[]
     */
    public function read($idProductLabel);

}
