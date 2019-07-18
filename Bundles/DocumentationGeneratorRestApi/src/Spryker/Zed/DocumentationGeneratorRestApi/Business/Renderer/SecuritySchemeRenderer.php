<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer;

use Generated\Shared\Transfer\SecuritySchemeComponentTransfer;
use Generated\Shared\Transfer\SecuritySchemeTransfer;
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
     * @param \Generated\Shared\Transfer\SecuritySchemeTransfer $securitySchemeTransfer
     *
     * @return array
     */
    public function render(SecuritySchemeTransfer $securitySchemeTransfer): array
    {
        $securitySchemeComponentTransfer = new SecuritySchemeComponentTransfer();
        $securitySchemeComponentTransfer->setName($securitySchemeTransfer->getName());
        $securitySchemeComponentTransfer->setType($securitySchemeTransfer->getType());
        $securitySchemeComponentTransfer->setScheme($securitySchemeTransfer->getScheme());

        $this->securitySchemeSpecificationComponent->setSecuritySchemeComponentTransfer($securitySchemeComponentTransfer);

        return $this->securitySchemeSpecificationComponent->getSpecificationComponentData();
    }
}
