<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;

interface PageManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingTemplateException
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     * @throws \Spryker\Zed\Cms\Business\Exception\PageExistsException
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function savePage(PageTransfer $page);

    /**
     * @param int $idPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    public function getPageById($idPage);

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $page
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function convertPageEntityToTransfer(SpyCmsPage $page);

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     *
     * @return void
     */
    public function touchPageActive(PageTransfer $page);

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createPageUrl(PageTransfer $pageTransfer);

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createPageUrlWithLocale(PageTransfer $page, $url, LocaleTransfer $localeTransfer);

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function savePageUrlAndTouch(PageTransfer $pageTransfer);

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $blockTransfer
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function savePageBlockAndTouch(PageTransfer $page, CmsBlockTransfer $blockTransfer);

}
