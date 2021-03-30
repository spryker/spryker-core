<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\ProductRelation\ProductRelationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductRelationConfig extends AbstractBundleConfig
{
    protected const PRODUCT_RELATION_UPDATE_CHUNK_SIZE = 1000;
    protected const PRODUCT_RELATION_UPDATE_CHUNK_SIZE_DEFAULT = 1000;

    /**
     * @api
     *
     * @return int
     */
    public function getRelatedProductsReadChunkSize(): int
    {
        return $this->get(
            ProductRelationConstants::PRODUCT_RELATION_READ_CHUNK,
            static::PRODUCT_RELATION_UPDATE_CHUNK_SIZE
        );
    }

    /**
     * @api
     *
     * @return int
     */
    public function getProductRelationUpdateChunkSize(): int
    {
        return $this->get(
            ProductRelationConstants::PRODUCT_RELATION_UPDATE_CHUNK,
            static::PRODUCT_RELATION_UPDATE_CHUNK_SIZE_DEFAULT
        );
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function findYvesHost()
    {
        $config = $this->getConfig();

        if ($config->hasKey(ApplicationConstants::BASE_URL_YVES)) {
            return $config->get(ApplicationConstants::BASE_URL_YVES);
        }

        return null;
    }
}
