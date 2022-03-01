<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Request;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueSparseResourceTransfer;

class RequestSparseFieldBuilder implements RequestBuilderInterface
{
    /**
     * @var string
     */
    protected const QUERY_FIELDS = 'fields';

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function extract(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $queryParameters = $glueRequestTransfer->getQueryFields();

        if (
            !isset($queryParameters[static::QUERY_FIELDS]) ||
            !is_array($queryParameters[static::QUERY_FIELDS])
        ) {
            return $glueRequestTransfer;
        }

        foreach ($queryParameters[static::QUERY_FIELDS] as $resource => $fields) {
            $glueSparseResourceTransfer = new GlueSparseResourceTransfer();
            $glueSparseResourceTransfer->setResourceType($resource);
            $glueSparseResourceTransfer->setFields(explode(',', $fields));
            $glueRequestTransfer->addSparseResource($glueSparseResourceTransfer);
        }

        return $glueRequestTransfer;
    }
}
