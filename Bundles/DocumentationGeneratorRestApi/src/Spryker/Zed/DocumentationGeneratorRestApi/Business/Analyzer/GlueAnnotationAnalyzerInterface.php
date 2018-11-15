<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Generated\Shared\Transfer\PathAnnotationsTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;

interface GlueAnnotationAnalyzerInterface
{
    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return \Generated\Shared\Transfer\PathAnnotationsTransfer
     */
    public function getResourceParametersFromPlugin(ResourceRoutePluginInterface $plugin): PathAnnotationsTransfer;
}
