<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component;

interface SpecificationComponentInterface
{
    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return array
     */
    public function getRequiredProperties(): array;
}
