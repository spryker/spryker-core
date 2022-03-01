<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Request;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\PaginationTransfer;

class RequestPaginationParameterBuilder implements RequestBuilderInterface
{
    /**
     * @var string
     */
    protected const QUERY_PAGINATION = 'page';

    /**
     * @var string
     */
    protected const PAGINATION_OFFSET = 'offset';

    /**
     * @var string
     */
    protected const PAGINATION_LIMIT = 'limit';

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function extract(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $queryParameters = $glueRequestTransfer->getQueryFields();

        if (!isset($queryParameters[static::QUERY_PAGINATION])) {
            return $glueRequestTransfer;
        }

        $page = $queryParameters[static::QUERY_PAGINATION];

        if (isset($page[static::PAGINATION_OFFSET], $page[static::PAGINATION_LIMIT])) {
            $glueRequestTransfer->setPagination(
                (new PaginationTransfer())
                    ->setOffset($page[static::PAGINATION_OFFSET])
                    ->setLimit($page[static::PAGINATION_LIMIT]),
            );
        }

        return $glueRequestTransfer;
    }
}
