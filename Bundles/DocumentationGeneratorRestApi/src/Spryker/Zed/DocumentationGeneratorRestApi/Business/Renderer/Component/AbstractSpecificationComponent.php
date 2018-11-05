<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

abstract class AbstractSpecificationComponent implements SpecificationComponentInterface
{
    /**
     * @return bool
     */
    public function isValid(): bool
    {
        foreach ($this->getRequiredProperties() as $property) {
            if ($property === null) {
                return false;
            }
        }

        return true;
    }
}
