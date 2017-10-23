<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\{bundle}\Dependency\Facade;

class {bundle}To{toBundle}Bridge implements {bundle}To{toBundle}Interface
{
    /**
     * @var \Spryker\Zed\{toBundle}\Business\{toBundle}FacadeInterface
     */
    protected ${toBundleVariable}Facade;

    /**
     * @param \Spryker\Zed\{toBundle}\Business\{toBundle}FacadeInterface ${toBundleVariable}Facade
     */
    public function __construct(${toBundleVariable}Facade)
    {
        $this->{toBundleVariable}Facade = ${toBundleVariable}Facade;
    }
}
