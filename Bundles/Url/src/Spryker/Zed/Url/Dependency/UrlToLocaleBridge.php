<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Dependency;

use Spryker\Zed\Locale\Business\LocaleFacade;
use Generated\Shared\Transfer\LocaleTransfer;

class UrlToLocaleBridge implements UrlToLocaleInterface
{

    /**
     * @var LocaleFacade
     */
    protected $localeFacade;

    /**
     * @param LocaleFacade $localeFacade
     */
    public function __construct($localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return LocaleTransfer
     */
    public function getLocale($localeName)
    {
        return $this->localeFacade->getLocale($localeName);
    }

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale()
    {
        return $this->localeFacade->getCurrentLocale();
    }

}
