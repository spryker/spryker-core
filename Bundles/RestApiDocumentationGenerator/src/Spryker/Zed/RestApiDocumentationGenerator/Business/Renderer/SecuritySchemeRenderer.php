<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer;

use Generated\Shared\Transfer\RestApiDocumentationSecuritySchemeTransfer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SecuritySchemeSpecificationComponent;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidatorInterface;

class SecuritySchemeRenderer implements SecuritySchemeRendererInterface
{
    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidatorInterface
     */
    protected $specificationComponentValidator;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidatorInterface $specificationComponentValidator
     */
    public function __construct(SpecificationComponentValidatorInterface $specificationComponentValidator)
    {
        $this->specificationComponentValidator = $specificationComponentValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationSecuritySchemeTransfer $securitySchemeTransfer
     *
     * @return array
     */
    public function render(RestApiDocumentationSecuritySchemeTransfer $securitySchemeTransfer): array
    {
        $securitySchemeComponent = new SecuritySchemeSpecificationComponent();
        $securitySchemeComponent->setName($securitySchemeTransfer->getName());
        $securitySchemeComponent->setType($securitySchemeTransfer->getType());
        $securitySchemeComponent->setScheme($securitySchemeTransfer->getScheme());

        if ($this->specificationComponentValidator->isValid($securitySchemeComponent)) {
            return $securitySchemeComponent->toArray();
        }

        return [];
    }
}
