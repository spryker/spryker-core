<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MerchantProductNotFoundException extends NotFoundHttpException
{
    /**
     * @param int $idMerchant
     * @param int $idProductAbstract
     */
    public function __construct(int $idMerchant, int $idProductAbstract)
    {
        parent::__construct($this->buildMessage($idMerchant, $idProductAbstract));
    }

    /**
     * @param int $idMerchant
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function buildMessage(int $idMerchant, int $idProductAbstract): string
    {
        return sprintf(
            'Product abstract is not found for product abstract id %d and merchant id %d.',
            $idProductAbstract,
            $idMerchant
        );
    }
}