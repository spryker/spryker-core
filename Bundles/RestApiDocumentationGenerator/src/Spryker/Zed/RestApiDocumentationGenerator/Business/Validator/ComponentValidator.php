<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Validator;

use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\ComponentInterface;

class ComponentValidator implements ComponentValidatorInterface
{
    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\ComponentInterface $component
     *
     * @return bool
     */
    public function isValid(ComponentInterface $component): bool
    {
        foreach ($component->getRequiredProperties() as $property) {
            if ($property === null) {
                return false;
            }
        }

        return true;
    }
}
