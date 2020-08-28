<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;

class MerchantToUrlFacadeBridge implements MerchantToUrlFacadeInterface
{
    /**
     * @var \Spryker\Zed\Url\Business\UrlFacadeInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\Url\Business\UrlFacadeInterface $urlFacade
     */
    public function __construct($urlFacade)
    {
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer|string $urlTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     * @param string|null $resourceType
     * @param int|null $idResource
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createUrl($urlTransfer, ?LocaleTransfer $localeTransfer = null, $resourceType = null, $idResource = null)
    {
        return $this->urlFacade->createUrl($urlTransfer, $localeTransfer, $resourceType, $idResource);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function updateUrl(UrlTransfer $urlTransfer)
    {
        return $this->urlFacade->updateUrl($urlTransfer);
    }
}
