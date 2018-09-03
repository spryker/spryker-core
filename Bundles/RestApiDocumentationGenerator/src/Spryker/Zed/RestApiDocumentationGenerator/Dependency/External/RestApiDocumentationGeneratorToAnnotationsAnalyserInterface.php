<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Dependency\External;

interface RestApiDocumentationGeneratorToAnnotationsAnalyserInterface
{
    /**
     * @param string $filename
     *
     * @return void
     */
    public function analyse($filename): void;

    /**
     * @return void
     */
    public function process(): void;

    /**
     * @return bool
     */
    public function validate(): bool;

    /**
     * @return array
     */
    public function getPaths(): array;
}
