<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionFactory getFactory()
 */
class JsonApiResponseFormatterPlugin extends AbstractPlugin implements ResponseFormatterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Build response for the JSON API convention.
     * - If `GlueErrorTransfer` exists, returns formatted error response.
     * - If `GlueResourceTransfer` is empty, returns formatted response with empty resources.
     * - Filters the attributes if the sparse fields `GlueSparseResourceTransfer` are provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function format(GlueResponseTransfer $glueResponseTransfer, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()->createJsonApiResponseBuilder()->buildResponse(
            $glueResponseTransfer,
            $glueRequestTransfer,
        );
    }
}
