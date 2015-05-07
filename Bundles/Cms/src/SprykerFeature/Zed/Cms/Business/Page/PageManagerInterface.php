<?php

namespace SprykerFeature\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\UrlUrlTransfer;
use SprykerFeature\Zed\Cms\Business\Exception\MissingPageException;
use SprykerFeature\Zed\Cms\Business\Exception\MissingTemplateException;
use SprykerFeature\Zed\Cms\Business\Exception\PageExistsException;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsPage;
use SprykerFeature\Zed\Url\Business\Exception\MissingUrlException;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;

interface PageManagerInterface
{
    /**
     * @param CmsPageTransfer $page
     *
     * @return CmsPageTransfer
     * @throws MissingTemplateException
     * @throws MissingPageException
     * @throws MissingUrlException
     * @throws PageExistsException
     */
    public function savePage(CmsPageTransfer $page);

    /**
     * @param int $idPage
     *
     * @return SpyCmsPage
     * @throws MissingPageException
     */
    public function getPageById($idPage);

    /**
     * @param SpyCmsPage $page
     *
     * @return CmsPageTransfer
     */
    public function convertPageEntityToTransfer(SpyCmsPage $page);

    /**
     * @param CmsPageTransfer $page
     */
    public function touchPageActive(CmsPageTransfer $page);

    /**
     * @param CmsPageTransfer $page
     * @param string $url
     *
     * @return UrlUrlTransfer
     * @throws UrlExistsException
     */
    public function createPageUrl(CmsPageTransfer $page, $url);
}
