<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Request;

use Generated\Shared\Transfer\GlueRequestTransfer;

class RequestRelationshipBuilder implements RequestBuilderInterface
{
    /**
     * @var string
     */
    protected const QUERY_INCLUDE = 'include';

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function extract(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $queryFields = $glueRequestTransfer->getQueryFields();
        if (!isset($queryFields[static::QUERY_INCLUDE]) || !$queryFields[static::QUERY_INCLUDE]) {
            return $glueRequestTransfer;
        }

        return $glueRequestTransfer->setIncludedRelationships(explode(',', trim($queryFields[static::QUERY_INCLUDE])));
    }
}
