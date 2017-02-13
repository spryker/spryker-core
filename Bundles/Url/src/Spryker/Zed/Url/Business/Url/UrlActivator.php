<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Url;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Url\Dependency\UrlToTouchInterface;
use Spryker\Zed\Url\UrlConfig;

class UrlActivator implements UrlActivatorInterface
{

    /**
     * @var \Spryker\Zed\Url\Dependency\UrlToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\Url\Dependency\UrlToTouchInterface $touchFacade
     */
    public function __construct(UrlToTouchInterface $touchFacade)
    {
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function activateUrl(UrlTransfer $urlTransfer)
    {
        $idUrl = $urlTransfer
            ->requireIdUrl()
            ->getIdUrl();

        $this->touchFacade->touchActive(UrlConfig::RESOURCE_TYPE_URL, $idUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function deactivateUrl(UrlTransfer $urlTransfer)
    {
        $idUrl = $urlTransfer
            ->requireIdUrl()
            ->getIdUrl();

        $this->touchFacade->touchDeleted(UrlConfig::RESOURCE_TYPE_URL, $idUrl);
    }

}
