<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Plugin;

use Spryker\Client\Search\Dependency\Plugin\FacetSearchResultValueTransformerPluginInterface;

class ProductRatingValueTransformer implements FacetSearchResultValueTransformerPluginInterface
{
    /**
     * @var int
     */
    public const CONVERSION_PRECISION = 100;

    /**
     * @var int
     */
    public const RATING_VALUE_TOLERANCE = 25;

    /**
     * @param array $value Range values.
     *
     * @return array
     */
    public function transformForDisplay($value)
    {
        if (isset($value['min'])) {
            $value['min'] = $this->normalizeRatingForDisplay($value['min']);
        }

        if (isset($value['max'])) {
            $value['max'] = $this->normalizeRatingForDisplay($value['max']);
        }

        return $value;
    }

    /**
     * @param array $value Range values.
     *
     * @return array
     */
    public function transformFromDisplay($value)
    {
        if (isset($value['min'])) {
            $value['min'] =
                $this->adjustLowerThreshold(
                    $this->normalizeRatingForFilter($value['min']),
                );
        }

        if (isset($value['max'])) {
            $value['max'] =
                $this->adjustUpperThreshold(
                    $this->normalizeRatingForFilter($value['max']),
                );
        }

        return $value;
    }

    /**
     * @param int $filteredRating
     *
     * @return int
     */
    protected function normalizeRatingForDisplay($filteredRating)
    {
        return (int)round($filteredRating / static::CONVERSION_PRECISION);
    }

    /**
     * @param int $displayedRating
     *
     * @return int
     */
    protected function normalizeRatingForFilter($displayedRating)
    {
        return $displayedRating * static::CONVERSION_PRECISION;
    }

    /**
     * @param int $filteredRating
     *
     * @return int
     */
    protected function adjustLowerThreshold($filteredRating)
    {
        return $filteredRating - static::RATING_VALUE_TOLERANCE;
    }

    /**
     * @param int $filteredRating
     *
     * @return int
     */
    protected function adjustUpperThreshold($filteredRating)
    {
        return $filteredRating + static::RATING_VALUE_TOLERANCE;
    }
}
