<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueHttp\Plugin\GlueContext;

use Generated\Shared\Transfer\GlueApiContextTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueContextExpanderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueHttp\GlueHttpFactory getFactory()
 */
class HttpGlueContextExpanderPlugin extends AbstractPlugin implements GlueContextExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds HTTP specific parts to the context (host, path from the url, method)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueApiContextTransfer $glueApiContextTransfer
     *
     * @return \Generated\Shared\Transfer\GlueApiContextTransfer
     */
    public function expand(GlueApiContextTransfer $glueApiContextTransfer): GlueApiContextTransfer
    {
        $httpExtractor = $this->getFactory()->createGlueContextHttpExpander();

        return $httpExtractor->expand($glueApiContextTransfer);
    }
}
