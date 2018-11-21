<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Url\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\UrlBuilder;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Url\Business\UrlFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class UrlDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $urlOverride
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function haveUrl(array $urlOverride = []): UrlTransfer
    {
        $urlTransfer = (new UrlBuilder())
            ->seed($urlOverride)
            ->build();

        if ($urlTransfer->getFkLocale() === null) {
            $currentLocaleTransfer = $this->getLocaleFacade()->getCurrentLocale();

            $urlTransfer->setFkLocale($currentLocaleTransfer->getIdLocale());
        }

        $this->getUrlFacade()->createUrl($urlTransfer);

        $this->debug(sprintf(
            'Inserted Url: %d',
            $urlTransfer->getIdUrl()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($urlTransfer) {
            $this->cleanupUrl($urlTransfer);
        });

        return $urlTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    protected function cleanupUrl(UrlTransfer $urlTransfer): void
    {
        $this->debug(sprintf('Deleting Url: %d', $urlTransfer->getIdUrl()));

        $this->getUrlFacade()->deleteUrl($urlTransfer);
    }

    /**
     * @return \Spryker\Zed\Url\Business\UrlFacadeInterface
     */
    protected function getUrlFacade(): UrlFacadeInterface
    {
        return $this->getLocator()->url()->facade();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }
}
