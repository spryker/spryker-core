<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer;

use Generated\Shared\Transfer\RestApiDocumentationSecuritySchemeTransfer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SecuritySchemeComponent;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\ComponentValidatorInterface;

class SecuritySchemeRenderer implements SecuritySchemeRendererInterface
{
    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\ComponentValidatorInterface
     */
    protected $validator;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\ComponentValidatorInterface $validator
     */
    public function __construct(ComponentValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationSecuritySchemeTransfer $securitySchemeTransfer
     *
     * @return array
     */
    public function render(RestApiDocumentationSecuritySchemeTransfer $securitySchemeTransfer): array
    {
        $securitySchemeComponent = new SecuritySchemeComponent();
        $securitySchemeComponent->setName($securitySchemeTransfer->getName());
        $securitySchemeComponent->setType($securitySchemeTransfer->getType());
        $securitySchemeComponent->setScheme($securitySchemeTransfer->getScheme());

        if ($this->validator->isValid($securitySchemeComponent)) {
            return $securitySchemeComponent->toArray();
        }

        return [];
    }
}
