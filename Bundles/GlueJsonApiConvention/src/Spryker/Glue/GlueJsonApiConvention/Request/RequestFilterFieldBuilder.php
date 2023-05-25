<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Request;

use Generated\Shared\Transfer\GlueFilterTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;

class RequestFilterFieldBuilder implements RequestBuilderInterface
{
    /**
     * @var string
     */
    protected const QUERY_FILTER = 'filter';

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function extract(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $queryParameters = $glueRequestTransfer->getQueryFields();

        if (!isset($queryParameters[static::QUERY_FILTER]) || !is_array($queryParameters[static::QUERY_FILTER])) {
            return $glueRequestTransfer;
        }

        foreach ($queryParameters[static::QUERY_FILTER] as $key => $value) {
            $explodedKey = explode('.', $key);
            $glueRequestTransfer->addFilter(
                (new GlueFilterTransfer())
                    ->setResource($explodedKey[0])
                    ->setField($explodedKey[1] ?? null)
                    ->setValue($value),
            );
        }

        return $glueRequestTransfer;
    }
}
