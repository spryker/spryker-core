<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

interface RestApiDocumentationSchemaGeneratorInterface
{
    /**
     * @param string $transferClassName
     * @param array $resourceRelationships
     *
     * @return void
     */
    public function addSchemaFromTransferClassName(string $transferClassName, array $resourceRelationships = []): void;

    /**
     * @return string
     */
    public function getLastAddedSchemaKey(): string;

    /**
     * @return array
     */
    public function getSchemas(): array;
}
