<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Glossary\Dependency\Facade;

use Spryker\Zed\Locale\Business\LocaleFacade;
use Generated\Shared\Transfer\LocaleTransfer;

class GlossaryToLocaleBridge implements GlossaryToLocaleInterface
{

    /**
     * @var LocaleFacade
     */
    protected $localeFacade;

    /**
     * GlossaryToLocaleBridge constructor.
     *
     * @param LocaleFacade
     */
    public function __construct($localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param string $localeName
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

    /**
     * @return array
     */
    public function getAvailableLocales()
    {
        return $this->localeFacade->getAvailableLocales();
    }

}
