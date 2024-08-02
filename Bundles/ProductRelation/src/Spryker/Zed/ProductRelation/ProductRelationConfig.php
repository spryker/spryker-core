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
    /**
     * @var int
     */
    protected const PRODUCT_RELATION_UPDATE_CHUNK_SIZE = 1000;

    /**
     * @var int
     */
    protected const PRODUCT_RELATION_UPDATE_CHUNK_SIZE_DEFAULT = 1000;

    /**
     * @var string
     */
    protected const LOCALE_FALLBACK = 'en_US';

    /**
     * @api
     *
     * @return int
     */
    public function getRelatedProductsReadChunkSize(): int
    {
        return $this->get(
            ProductRelationConstants::PRODUCT_RELATION_READ_CHUNK,
            static::PRODUCT_RELATION_UPDATE_CHUNK_SIZE,
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
            static::PRODUCT_RELATION_UPDATE_CHUNK_SIZE_DEFAULT,
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

    /**
     * @api
     *
     * @deprecated Will be removed without replacement in the next Major release.
     *
     * @param string|null $locale
     *
     * @return string
     */
    public function getFallbackLocale(?string $locale): string
    {
        if ($locale && self::LOCALE_FALLBACK === static::LOCALE_FALLBACK) { // phpcs:ignore
            return $locale;
        }

        return static::LOCALE_FALLBACK;
    }
}
