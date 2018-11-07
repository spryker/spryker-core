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
class SecuritySchemeSpecificationComponent implements SecuritySchemeSpecificationComponentInterface
{
    protected const KEY_TYPE = 'type';
    protected const KEY_SCHEME = 'scheme';
    protected const KEY_IN = 'in';

    /**
     * @var \Generated\Shared\Transfer\OpenApiSpecificationSecuritySchemeComponentTransfer $securitySchemeComponentTransfer
     */
    protected $securitySchemeComponentTransfer;

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationSecuritySchemeComponentTransfer $securitySchemeComponentTransfer
     *
     * @return void
     */
    public function setSecuritySchemeComponentTransfer(OpenApiSpecificationSecuritySchemeComponentTransfer $securitySchemeComponentTransfer): void
    {
        $this->securitySchemeComponentTransfer = $securitySchemeComponentTransfer;
    }

    /**
     * @return array
     */
    public function getSpecificationComponentData(): array
    {
        if (!$this->validateSecuritySchemeComponentTransfer()) {
            return [];
        }

        $securitySchemaData[$this->securitySchemeComponentTransfer->getName()][static::KEY_TYPE] = $this->securitySchemeComponentTransfer->getType();
        $securitySchemaData[$this->securitySchemeComponentTransfer->getName()][static::KEY_SCHEME] = $this->securitySchemeComponentTransfer->getScheme();

        return $securitySchemaData;
    }

    /**
     * @return bool
     */
    protected function validateSecuritySchemeComponentTransfer(): bool
    {
        if ($this->securitySchemeComponentTransfer === null) {
            return false;
        }

        $this->securitySchemeComponentTransfer->requireName();
        $this->securitySchemeComponentTransfer->requireType();
        $this->securitySchemeComponentTransfer->requireScheme();

        return true;
    }
}
