<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Request;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\SortTransfer;

class RequestSortParameterBuilder implements RequestBuilderInterface
{
    /**
     * @var string
     */
    protected const QUERY_SORT = 'sort';

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function extract(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
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
