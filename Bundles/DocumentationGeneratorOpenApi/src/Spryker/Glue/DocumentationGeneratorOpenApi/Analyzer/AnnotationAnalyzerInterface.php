<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface AnnotationAnalyzerInterface
{
    /**
     * @param string $classPath
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $annotationsTransfer
     * @param string|null $actionName
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    public function getResourceParametersFromResource(
        string $classPath,
        AbstractTransfer $annotationsTransfer,
        ?string $actionName
    ): ?AbstractTransfer;
}
