<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\OpenApiSpecificationSecuritySchemeComponentTransfer;

/**
 * Specification:
 *  - This component describes a security scheme that can be used by the operations.
 *  - This component covers Security Scheme Object in OpenAPI specification format (see https://swagger.io/specification/#securitySchemeObject).
 */
class SecuritySchemeSpecificationComponent extends AbstractSpecificationComponent implements SecuritySchemeSpecificationComponentInterface
{
    protected const KEY_TYPE = 'type';
    protected const KEY_SCHEME = 'scheme';
    protected const KEY_IN = 'in';

    /**
     * @var \Generated\Shared\Transfer\OpenApiSpecificationSecuritySchemeComponentTransfer $specificationComponentTransfer
     */
    protected $specificationComponentTransfer;

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationSecuritySchemeComponentTransfer $securitySchemeComponentTransfer
     *
     * @return void
     */
    public function setSecuritySchemeComponentTransfer(OpenApiSpecificationSecuritySchemeComponentTransfer $securitySchemeComponentTransfer): void
    {
        $this->specificationComponentTransfer = $securitySchemeComponentTransfer;
    }

    /**
     * @return array
     */
    public function getSpecificationComponentData(): array
    {
        $securitySchemaData[$this->specificationComponentTransfer->getName()][static::KEY_TYPE] = $this->specificationComponentTransfer->getType();
        $securitySchemaData[$this->specificationComponentTransfer->getName()][static::KEY_SCHEME] = $this->specificationComponentTransfer->getScheme();

        return $securitySchemaData;
    }

    /**
     * @return array
     */
    protected function getRequiredProperties(): array
    {
        return [
            $this->specificationComponentTransfer->getName(),
            $this->specificationComponentTransfer->getType(),
            $this->specificationComponentTransfer->getScheme(),
        ];
    }
}
