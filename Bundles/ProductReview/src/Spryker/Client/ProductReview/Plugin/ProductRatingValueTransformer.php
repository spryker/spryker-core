<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Plugin;

use Spryker\Client\Search\Dependency\Plugin\FacetSearchResultValueTransformerPluginInterface;

class ProductRatingValueTransformer implements FacetSearchResultValueTransformerPluginInterface
{
    public const CONVERSION_PRECISION = 100;
    public const RATING_VALUE_TOLERANCE = 25;
    protected const RANGE_MIN_PARAMETER = 'min';
    protected const RANGE_MAX_PARAMETER = 'max';

    /**
     * @param array $rangeValues
     *
     * @return array
     */
    public function transformForDisplay($rangeValues)
    {
        if (isset($rangeValues[self::RANGE_MIN_PARAMETER])) {
            $rangeValues[self::RANGE_MIN_PARAMETER] = $this->normalizeRatingForDisplay($rangeValues[self::RANGE_MIN_PARAMETER ]);
        }

        if (isset($rangeValues[self::RANGE_MAX_PARAMETER])) {
            $rangeValues[self::RANGE_MAX_PARAMETER] = $this->normalizeRatingForDisplay($rangeValues[self::RANGE_MAX_PARAMETER]);
        }

        return $rangeValues;
    }

    /**
     * @param array $rangeValues
     *
     * @return array
     */
    public function transformFromDisplay($rangeValues)
    {
        if (isset($rangeValues[self::RANGE_MIN_PARAMETER]) && is_numeric($rangeValues[self::RANGE_MIN_PARAMETER])) {
            $rangeValues[self::RANGE_MIN_PARAMETER] =
                $this->adjustLowerThreshold(
                    $this->normalizeRatingForFilter($rangeValues[self::RANGE_MIN_PARAMETER])
                );
        }

        if (isset($rangeValues[self::RANGE_MAX_PARAMETER]) && is_numeric($rangeValues[self::RANGE_MAX_PARAMETER])) {
            $rangeValues[self::RANGE_MAX_PARAMETER] =
                $this->adjustUpperThreshold(
                    $this->normalizeRatingForFilter($rangeValues[self::RANGE_MAX_PARAMETER])
                );
        }

        return $rangeValues;
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
