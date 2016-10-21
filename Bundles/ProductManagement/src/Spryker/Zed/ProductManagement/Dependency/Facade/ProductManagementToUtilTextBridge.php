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
    protected $utilFacade;

    /**
     * @param \Spryker\Zed\UtilText\Business\UtilTextFacadeInterface $utilFacade
     */
    public function __construct($utilFacade)
    {
        $this->utilFacade = $utilFacade;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function generateSlug($value)
    {
        return $this->utilFacade->generateSlug($value);
    }

}
