<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer;

interface PluginAnalyzerInterface
{
    /**
     * @return void
     */
    public function createRestApiDocumentationFromPlugins(): void;

    /**
     * @return array
     */
    public function getRestApiDocumentationData(): array;
}
