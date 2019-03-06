<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductBundle;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\ProductBundle\ProductBundleServiceFactory getFactory()
 */
class ProductBundleService extends AbstractService implements ProductBundleServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function convertToInt(float $value): int
    {
        return $this->getFactory()
            ->createFloatConverter()
            ->convertToInt($value);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param float $value
     *
     * @return float
     */
    public function round(float $value): float
    {
        return $this->getFactory()
            ->createFloatConverter()
            ->round($value);
    }
}
