<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;

interface ProductUrlGeneratorInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return mixed
     */
    public function createAndTouchProductUrls($idProductAbstract);

    /**
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributes
     * @param int $idProductAbstract
     *
     * @return string
     */
    public function generateProductUrl(LocalizedAttributesTransfer $localizedAttributes, $idProductAbstract);
}
