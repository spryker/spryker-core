<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Dependency;

/**
 * @property \Spryker\Zed\Propel\Business\PropelFacadeInterface $propelFacade
 */
class UrlToPropelBridge implements UrlToPropelInterface
{
    /**
     * @var \Spryker\Zed\Propel\Business\PropelFacadeInterface
     */
    protected $propelFacade;

    /**
     * @param \Spryker\Zed\Propel\Business\PropelFacadeInterface $propelFacade
     */
    public function __construct($propelFacade)
    {
        $this->propelFacade = $propelFacade;
    }

    public function isCollationCaseSensitive(): bool
    {
        return $this->propelFacade->isCollationCaseSensitive();
    }
}
