<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Builder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface;

interface RequestBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function build(
        GlueRequestTransfer $glueRequestTransfer,
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConventionPlugin
    ): GlueRequestTransfer;
}
