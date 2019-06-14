<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use InvalidArgumentException;
use Spryker\Zed\Twig\Communication\Plugin\AbstractTwigExtensionPlugin;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\TwigFilter;

/**
 * @method \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface getFacade()
 * @method \Spryker\Zed\Glossary\Communication\GlossaryCommunicationFactory getFactory()
 */
class TwigTranslatorPlugin extends AbstractTwigExtensionPlugin implements TranslatorInterface
{
    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer|null
     */
    protected $localeTransfer;

    /**
     * @var string|null
     */
    protected $localeName;

    /**
     * @api
     *
     * @return string
     */
    public function getName()
    {
        return 'translator';
    }

    /**
     * @api
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new TwigFilter('trans', [$this, 'trans']),
            new TwigFilter('transchoice', [$this, 'transchoice']),
        ];
    }

    /**
     * Specification:
     * - Translates the given message.
     *
     * @api
     *
     * @param string $identifier
     * @param array $parameters
     * @param string|null $domain
     * @param string|null $locale
     *
     * @return string
     */
    public function trans($identifier, array $parameters = [], $domain = null, $locale = null)
    {
        if ($locale !== null) {
            $this->setLocale($locale);
        }
        $localeTransfer = $this->getLocaleTransfer();

        if ($this->getFacade()->hasTranslation($identifier, $localeTransfer)) {
            $identifier = $this->getFacade()->translate($identifier, $parameters, $localeTransfer);
        }

        return $identifier;
    }

    /**
     * Specification:
     * - Translates the given choice message by choosing a translation according to a number.
     *
     * @api
     *
     * @param string $identifier
     * @param int $number
     * @param array $parameters
     * @param string|null $domain
     * @param string|null $locale
     *
     * @throws \InvalidArgumentException
     *
     * @return string The translated string
     */
    public function transChoice($identifier, $number, array $parameters = [], $domain = null, $locale = null)
    {
        if ($locale !== null) {
            $this->setLocale($locale);
        }
        $localeTransfer = $this->getLocaleTransfer();

        $ids = explode('|', $identifier);

        if ($number === 1) {
            if (!$this->getFacade()->hasTranslation($ids[0], $localeTransfer)) {
                return $ids[0];
            }

            return $this->getFacade()->translate($ids[0], $parameters, $localeTransfer);
        }

        if (!isset($ids[1])) {
            throw new InvalidArgumentException(sprintf('The message "%s" cannot be pluralized, because it is missing a plural (e.g. "There is one apple|There are %%count%% apples").', $identifier));
        }

        if (!$this->getFacade()->hasTranslation($ids[1], $localeTransfer)) {
            return $ids[1];
        }

        return $this->getFacade()->translate($ids[1], $parameters, $localeTransfer);
    }

    /**
     * @api
     *
     * @param string $localeName
     *
     * @return $this
     */
    public function setLocale($localeName)
    {
        $this->localeName = $localeName;

        return $this;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getLocale()
    {
        return $this->localeName;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return $this
     */
    public function setLocaleTransfer(LocaleTransfer $localeTransfer)
    {
        $this->localeTransfer = $localeTransfer;

        return $this;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransfer()
    {
        if (!$this->localeTransfer) {
            if ($this->getLocale() === null) {
                throw new InvalidArgumentException('No locale or localeTransfer specified. You need to set a localeName or a LocaleTransfer, otherwise translation can not properly work.');
            }
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->setLocaleName($this->localeName);

            $this->localeTransfer = $localeTransfer;
        }

        return $this->localeTransfer;
    }
}
