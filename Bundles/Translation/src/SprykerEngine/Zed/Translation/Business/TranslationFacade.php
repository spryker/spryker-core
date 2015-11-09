<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Translation\Business;

use SprykerEngine\Shared\Translation\TranslationInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\LocaleTransfer;

/**
 * @method TranslationDependencyContainer getDependencyContainer()
 */
class TranslationFacade extends AbstractFacade implements TranslationInterface
{

    /**
     * @param string $id
     * @param array $parameters
     * @param string $domain
     * @param LocaleTransfer $locale
     *
     * @return string
     */
    public function translate($id, array $parameters = [], $domain = null, LocaleTransfer $locale = null)
    {
        return $this->getDependencyContainer()
            ->createTranslator($locale->getLocaleName())
            ->trans($id, $parameters, $domain, $locale->getLocaleName());
    }

    /**
     * @param string $id
     * @param int $number
     * @param array $parameters
     * @param string $domain
     * @param LocaleTransfer $locale
     *
     * @return string
     */
    public function translateChoice($id, $number, array $parameters = [], $domain = null, LocaleTransfer $locale = null)
    {
        return $this->getDependencyContainer()
            ->createTranslator($locale->getLocaleName())
            ->transChoice($id, $number, $parameters, $domain, $locale->getLocaleName());
    }

    /**
     * @param string $locale
     *
     * @return TranslatorInterface
     */
    public function getTranslator($locale)
    {
        return $this->getDependencyContainer()->createTranslator($locale);
    }

}
