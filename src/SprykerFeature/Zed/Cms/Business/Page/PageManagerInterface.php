<?php

namespace SprykerFeature\Zed\Cms\Business\Page;

use SprykerFeature\Shared\Cms\Transfer\Page;
use SprykerFeature\Shared\Url\Transfer\Url;
use SprykerFeature\Zed\Cms\Business\Exception\MissingPageException;
use SprykerFeature\Zed\Cms\Business\Exception\MissingTemplateException;
use SprykerFeature\Zed\Cms\Business\Exception\PageExistsException;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsPage;
use SprykerFeature\Zed\Url\Business\Exception\MissingUrlException;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;

interface PageManagerInterface
{
    /**
     * @param Page $page
     *
     * @return Page
     * @throws MissingTemplateException
     * @throws MissingPageException
     * @throws MissingUrlException
     * @throws PageExistsException
     */
    public function savePage(Page $page);

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
     * @return Page
     */
    public function convertPageEntityToTransfer(SpyCmsPage $page);

    /**
     * @param Page $page
     */
    public function touchPageActive(Page $page);

    /**
     * @param Page $page
     * @param string $url
     *
     * @return Url
     * @throws UrlExistsException
     */
    public function createPageUrl(Page $page, $url);
}
