<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Validator;

use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SpecificationComponentInterface;

interface SpecificationComponentValidatorInterface
{
    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SpecificationComponentInterface $component
     *
     * @return bool
     */
    public function isValid(SpecificationComponentInterface $component): bool;
}
