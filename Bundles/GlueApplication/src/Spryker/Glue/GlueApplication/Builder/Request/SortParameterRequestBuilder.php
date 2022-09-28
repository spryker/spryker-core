<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Builder\Request;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\SortTransfer;

class SortParameterRequestBuilder implements RequestBuilderInterface
{
    /**
     * @var string
     */
    protected const QUERY_SORT = 'sort';

    /**
     * Specification:
     * - Extracts `GlueRequestTransfer.sortings` from the `GlueRequestTransfer.queryFields`.
     * - Looks for `GlueRequestTransfer.queryFields` equal to "sort".
     * - Splits sort values by comma, interprets each part as sort field.
     * - Interprets minus sign before the value as DESC direction, otherwise ASC order applies.
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function build(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $queryParameters = $glueRequestTransfer->getQueryFields();

        if (empty($queryParameters[static::QUERY_SORT])) {
            return $glueRequestTransfer;
        }

        $sortFields = explode(',', $queryParameters[static::QUERY_SORT]);
        foreach ($sortFields as $field) {
            $isAscending = true;
            if ($field[0] === '-') {
                $isAscending = false;
                $field = trim($field, '-');
            }

            $glueRequestTransfer->addSorting(
                (new SortTransfer())
                    ->setField($field)
                    ->setIsAscending($isAscending),
            );
        }

        return $glueRequestTransfer;
    }
}
