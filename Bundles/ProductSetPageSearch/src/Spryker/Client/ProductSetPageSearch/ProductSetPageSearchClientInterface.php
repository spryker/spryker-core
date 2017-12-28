<?php

namespace Spryker\Client\ProductSetPageSearch;

interface ProductSetPageSearchClientInterface
{
    /**
     * Specification:
     * - Returns a list of Product Sets from Search.
     * - The results are sorted by weight descending.
     *
     * @api
     *
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function getProductSetList($limit = null, $offset = null);
}
