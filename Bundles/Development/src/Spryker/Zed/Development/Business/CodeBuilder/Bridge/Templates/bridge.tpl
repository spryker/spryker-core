<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\{bundle}\Dependency\Facade;

class {bundle}To{toBundle}Bridge implements {bundle}To{toBundle}Interface
{

    /**
     * @var \Spryker\Zed\{toBundle}\Business\{toBundle}FacadeInterface
     */
    protected ${toBundleVariable}Facade;

    /**
     * @param \Spryker\Zed\{toBundle}\Business\{toBundle}Facade ${toBundleVariable}FacadeInterface
     */
    public function __construct(${toBundleVariable}Facade)
    {
        $this->{toBundleVariable}Facade = ${toBundleVariable}Facade;
    }

}
