<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cms\Dependency\Facade;

use Spryker\Zed\Url\Business\UrlFacade;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;

class CmsToUrlBridge implements CmsToUrlInterface
{

    /**
     * @var UrlFacade
     */
    protected $urlFacade;

    /**
     * CmsToUrlBridge constructor.
     *
     * @param UrlFacade $urlFacade
     */
    public function __construct($urlFacade)
    {
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param string $url
     * @param string $resourceType
     * @param int $idResource
     *
     * @throws PropelException
     * @throws UrlExistsException
     *
     * @return UrlTransfer
     */
    public function createUrlForCurrentLocale($url, $resourceType, $idResource)
    {
        return $this->urlFacade->createUrlForCurrentLocale($url, $resourceType, $idResource);
    }

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param string $resourceType
     * @param int $idResource
     *
     * @throws PropelException
     * @throws UrlExistsException
     *
     * @return UrlTransfer
     */
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $idResource)
    {
        return $this->urlFacade->createUrl($url, $locale, $resourceType, $idResource);
    }

    /**
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlActive($idUrl)
    {
        $this->urlFacade->touchUrlActive($idUrl);
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function hasUrl($url)
    {
        return $this->urlFacade->hasUrl($url);
    }

    /**
     * @param string $toUrl
     * @param int $status
     *
     * @return RedirectTransfer
     */
    public function createRedirectAndTouch($toUrl, $status = 303)
    {
        return $this->urlFacade->createRedirectAndTouch($toUrl, $status);
    }

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param int $idUrlRedirect
     *
     * @return UrlTransfer
     */
    public function saveRedirectUrlAndTouch($url, LocaleTransfer $locale, $idUrlRedirect)
    {
        return $this->urlFacade->saveRedirectUrlAndTouch($url, $locale, $idUrlRedirect);
    }

}
