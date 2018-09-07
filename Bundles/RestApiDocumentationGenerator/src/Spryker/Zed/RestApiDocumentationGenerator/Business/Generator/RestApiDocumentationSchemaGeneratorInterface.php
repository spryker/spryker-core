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
     *
     * @return void
     */
    public function addRequestSchemaFromTransferClassName(string $transferClassName): void;

    /**
     * @param string $transferClassName
     * @param array $resourceRelationships
     *
     * @return void
     */
    public function addResponseSchemaFromTransferClassName(string $transferClassName, array $resourceRelationships = []): void;

    /**
     * @return string
     */
    public function getLastAddedRequestSchemaKey(): string;

    /**
     * @return string
     */
    public function getLastAddedResponseSchemaKey(): string;

    /**
     * @return array
     */
    public function getSchemas(): array;
}
