<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductAbstractNotFoundException extends NotFoundHttpException
{
    /**
     * @param int $idProductAbstract
     */
    public function __construct(int $idProductAbstract)
    {
        parent::__construct($this->buildMessage($idProductAbstract));
    }

    /**
     * @param int $idProductConcrete
     *
     * @return string
     */
    protected function buildMessage(int $idProductConcrete): string
    {
        return sprintf(
            'Product abstract is not found for product abstract id %d.',
            $idProductConcrete
        );
    }
}
