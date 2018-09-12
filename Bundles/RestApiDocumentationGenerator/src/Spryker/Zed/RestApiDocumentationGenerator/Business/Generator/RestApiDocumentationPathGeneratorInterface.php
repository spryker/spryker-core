<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

interface RestApiDocumentationPathGeneratorInterface
{
    /**
     * @param string $resource
     * @param string $resourcePath
     * @param string $responseSchema
     * @param string $errorSchema
     * @param bool $isProtected
     *
     * @return void
     */
    public function addGetPath(string $resource, string $resourcePath, string $responseSchema, string $errorSchema, bool $isProtected): void;

    /**
     * @param string $resource
     * @param string $resourcePath
     * @param string $requestSchema
     * @param string $responseSchema
     * @param string $errorSchema
     * @param bool $isProtected
     *
     * @return void
     */
    public function addPostPath(string $resource, string $resourcePath, string $requestSchema, string $responseSchema, string $errorSchema, bool $isProtected): void;

    /**
     * @param string $resource
     * @param string $resourcePath
     * @param string $requestSchema
     * @param string $responseSchema
     * @param string $errorSchema
     * @param bool $isProtected
     *
     * @return void
     */
    public function addPatchPath(string $resource, string $resourcePath, string $requestSchema, string $responseSchema, string $errorSchema, bool $isProtected): void;

    /**
     * @param string $resource
     * @param string $resourcePath
     * @param string $errorSchema
     * @param bool $isProtected
     *
     * @return void
     */
    public function addDeletePath(string $resource, string $resourcePath, string $errorSchema, bool $isProtected): void;

    /**
     * @return array
     */
    public function getPaths(): array;
}
