<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer;

use Generated\Shared\Transfer\OpenApiSpecificationSecuritySchemeComponentTransfer;
use Generated\Shared\Transfer\OpenApiSpecificationSecuritySchemeTransfer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SecuritySchemeSpecificationComponentInterface;

class SecuritySchemeRenderer implements SecuritySchemeRendererInterface
{
    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SecuritySchemeSpecificationComponentInterface
     */
    protected $securitySchemeSpecificationComponent;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SecuritySchemeSpecificationComponentInterface $securitySchemeSpecificationComponent
     */
    public function __construct(SecuritySchemeSpecificationComponentInterface $securitySchemeSpecificationComponent)
    {
        $this->securitySchemeSpecificationComponent = $securitySchemeSpecificationComponent;
    }

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationSecuritySchemeTransfer $securitySchemeTransfer
     *
     * @return array
     */
    public function render(OpenApiSpecificationSecuritySchemeTransfer $securitySchemeTransfer): array
    {
        $securitySchemeComponentTransfer = new OpenApiSpecificationSecuritySchemeComponentTransfer();
        $securitySchemeComponentTransfer->setName($securitySchemeTransfer->getName());
        $securitySchemeComponentTransfer->setType($securitySchemeTransfer->getType());
        $securitySchemeComponentTransfer->setScheme($securitySchemeTransfer->getScheme());

        $this->securitySchemeSpecificationComponent->setSecuritySchemeComponentTransfer($securitySchemeComponentTransfer);

        return $this->securitySchemeSpecificationComponent->getSpecificationComponentData();
    }
}
