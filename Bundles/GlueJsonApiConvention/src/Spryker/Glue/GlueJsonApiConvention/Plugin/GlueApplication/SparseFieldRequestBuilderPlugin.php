<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionFactory getFactory()
 */
class SparseFieldRequestBuilderPlugin extends AbstractPlugin implements RequestBuilderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Extracts `fields` parameters from the `GlueRequestTransfer.queryFields`.
     * - Expects the fields to be a nested array parameter with key being the resource type and values - attribute names.
     * - Expands `GlueRequestTransfer` with `GlueSparseResourceTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function build(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        return $this->getFactory()->createRequestSparseFieldBuilder()->extract($glueRequestTransfer);
    }
}
