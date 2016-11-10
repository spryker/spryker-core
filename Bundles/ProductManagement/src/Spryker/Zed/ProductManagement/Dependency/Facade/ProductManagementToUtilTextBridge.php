<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

class ProductManagementToUtilTextBridge implements ProductManagementToUtilTextInterface
{

    /**
     * @var \Spryker\Zed\Url\Business\UrlFacadeInterface
     */
    protected $utilTextFacade;

    /**
     * @param \Spryker\Zed\UtilText\Business\UtilTextFacadeInterface $utilTextFacade
     */
    public function __construct($utilTextFacade)
    {
        $this->utilTextFacade = $utilTextFacade;
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
