<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Dependency\Facade;

use Spryker\Zed\Url\Business\UrlFacade;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;

class ProductToUrlBridge implements ProductToUrlInterface
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
     * @param LocaleTransfer $locale
     * @param string $resourceType
     * @param int $resourceId
     *
     * @throws PropelException
     * @throws UrlExistsException
     *
     * @return UrlTransfer
     */
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $resourceId)
    {
        return $this->urlFacade->createUrl($url, $locale, $resourceType, $resourceId);
    }

    /**
     * @param int $idUrl
     */
    public function touchUrlActive($idUrl)
    {
        $this->urlFacade->touchUrlActive($idUrl);
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return UrlTransfer
     */
    public function getUrlByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale)
    {
        return $this->urlFacade->getUrlByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale);
    }

}
