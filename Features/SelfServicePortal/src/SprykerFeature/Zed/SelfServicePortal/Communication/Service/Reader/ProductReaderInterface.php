<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductReaderInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete(int $idProductConcrete): ProductConcreteTransfer;
}
