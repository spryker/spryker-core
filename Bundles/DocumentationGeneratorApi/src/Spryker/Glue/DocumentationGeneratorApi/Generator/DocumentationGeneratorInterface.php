<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApi\Generator;

interface DocumentationGeneratorInterface
{
    /**
     * @param array<string> $applications
     *
     * @return void
     */
    public function generateDocumentation(array $applications = []): void;
}
