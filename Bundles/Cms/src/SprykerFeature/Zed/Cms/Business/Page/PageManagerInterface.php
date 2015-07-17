<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\PageTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use SprykerFeature\Zed\Cms\Business\Exception\MissingPageException;
use SprykerFeature\Zed\Cms\Business\Exception\MissingTemplateException;
use SprykerFeature\Zed\Cms\Business\Exception\PageExistsException;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsPage;
use SprykerFeature\Zed\Url\Business\Exception\MissingUrlException;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;

interface PageManagerInterface
{

    /**
     * @param PageTransfer $page
     *
     * @throws MissingTemplateException
     * @throws MissingPageException
     * @throws MissingUrlException
     * @throws PageExistsException
     *
     * @return PageTransfer
     */
    public function savePage(PageTransfer $page);

    /**
     * @param int $idPage
     *
     * @throws MissingPageException
     *
     * @return SpyCmsPage
     */
    public function getPageById($idPage);

    /**
     * @param SpyCmsPage $page
     *
     * @return PageTransfer
     */
    public function convertPageEntityToTransfer(SpyCmsPage $page);

    /**
     * @param PageTransfer $page
     */
    public function touchPageActive(PageTransfer $page);

    /**
     * @param PageTransfer $page
     * @param string $url
     *
     * @throws UrlExistsException
     *
     * @return UrlTransfer
     */
    public function createPageUrl(PageTransfer $page, $url);

}
