<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Dependency;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Locale\Business\Exception\MissingLocaleException;

class UrlToLocaleBridge implements UrlToLocaleInterface
{

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * CategoryToLocaleBridge constructor.
     *
     * @param \Spryker\Zed\Locale\Business\LocaleFacade $localeFacade
     */
    public function __construct($localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param string $localeName
     *
     * @throws MissingLocaleException
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
