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

    protected const PARAMETER_RANGE_MIN = 'min';
    protected const PARAMETER_RANGE_MAX = 'max';

    /**
     * @param array $rangeValues
     *
     * @return array
     */
    public function transformForDisplay($rangeValues)
    {
        if (isset($rangeValues[static::PARAMETER_RANGE_MIN])) {
            $rangeValues[static::PARAMETER_RANGE_MIN] = $this->normalizeRatingForDisplay($rangeValues[static::PARAMETER_RANGE_MIN]);
        }

        if (isset($rangeValues[static::PARAMETER_RANGE_MAX])) {
            $rangeValues[static::PARAMETER_RANGE_MAX] = $this->normalizeRatingForDisplay($rangeValues[static::PARAMETER_RANGE_MAX]);
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
        if (isset($rangeValues[static::PARAMETER_RANGE_MIN])) {
            $rangeValues[static::PARAMETER_RANGE_MIN] =
                $this->adjustLowerThreshold(
                    $this->normalizeRatingForFilter($rangeValues[static::PARAMETER_RANGE_MIN])
                );
        }

        if (isset($rangeValues[static::PARAMETER_RANGE_MAX])) {
            $rangeValues[static::PARAMETER_RANGE_MAX] =
                $this->adjustUpperThreshold(
                    $this->normalizeRatingForFilter($rangeValues[static::PARAMETER_RANGE_MAX])
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
