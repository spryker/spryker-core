<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Glossary\Dependency\Facade;

use Spryker\Zed\Locale\Business\LocaleFacade;

class GlossaryToLocaleBridge implements GlossaryToLocaleInterface
{

    /**
     * @var LocaleFacade
     */
    protected $localeFacade;

    /**
     * GlossaryToLocaleBridge constructor.
     *
     * @param \Spryker\Zed\Locale\Business\LocaleFacade
     */
    public function __construct($localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale($localeName)
    {
        return $this->localeFacade->getLocale($localeName);
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale()
    {
        return $this->localeFacade->getCurrentLocale();
    }

    /**
     * @return array
     */
    public function getAvailableLocales()
    {
        return $this->localeFacade->getAvailableLocales();
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function createLocale($localeName)
    {
        return $this->localeFacade->createLocale($localeName);
    }

}
