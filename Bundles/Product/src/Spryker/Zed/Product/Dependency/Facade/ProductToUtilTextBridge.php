<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency\Facade;

class ProductToUtilTextBridge implements ProductToUtilTextInterface
{

    /**
     * @var \Spryker\Zed\UtilText\Business\UtilTextFacadeInterface
     */
    protected $utilTextFacade;

    /**
     * @param \Spryker\Zed\UtilText\Business\UtilTextFacadeInterface $utilFacade
     */
    public function __construct($utilFacade)
    {
        $this->utilTextFacade = $utilFacade;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function generateSlug($value)
    {
        return $this->utilTextFacade->generateSlug($value);
    }

}
