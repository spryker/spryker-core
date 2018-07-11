<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\UrlRedirectBuilder;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CmsRedirectHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveUrlRedirect(array $seed = [])
    {
        $urlRedirectTransfer = (new UrlRedirectBuilder($seed))
            ->withSource()
            ->build();

        $idLocale = $this->getLocator()->locale()->facade()->getCurrentLocale()->getIdLocale();
        $urlRedirectTransfer->getSource()->setFkLocale($idLocale);

        $urlFacade = $this->getLocator()->url()->facade();
        $urlFacade->createUrlRedirect($urlRedirectTransfer);

        $this->debug(sprintf(
            'Inserted UrlRedirect: %d',
            $urlRedirectTransfer->getIdUrlRedirect()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($urlRedirectTransfer) {
            $this->cleanupUrlRedirect($urlRedirectTransfer->getSource()->getIdUrl());
        });

        return $urlRedirectTransfer;
    }

    /**
     * @return \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    private function getUrlQuery()
    {
        return $this->getLocator()->url()->queryContainer();
    }

    /**
     * @param int $idUrl
     *
     * @return void
     */
    private function cleanupUrlRedirect($idUrl)
    {
        $this->debug(sprintf('Deleting Url redirect: %d', $idUrl));

        $this->getUrlQuery()
            ->queryUrlRedirectByIdUrl($idUrl)
            ->delete();
    }
}
