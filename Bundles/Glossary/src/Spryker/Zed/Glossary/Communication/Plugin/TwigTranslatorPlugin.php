<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use InvalidArgumentException;
use Spryker\Zed\Twig\Communication\Plugin\AbstractTwigExtensionPlugin;
use Symfony\Contracts\Translation\TranslatorInterface;
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName()
    {
        return 'translator';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Twig\TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('trans', [$this, 'trans']),
            new TwigFilter('transchoice', [$this, 'transchoice']),
        ];
    }

    /**
     * {@inheritDoc}
     * Specification:
     * - Translates the given message.
     *
     * @api
     *
     * @param string $id
     * @param array $parameters
     * @param string|null $domain
     * @param string|null $locale
     *
     * @return string
     */
    public function trans($id, array $parameters = [], ?string $domain = null, ?string $locale = null)
    {
        if ($locale !== null) {
            $this->setLocale($locale);
        }
        $localeTransfer = $this->getLocaleTransfer();

        if ($this->getFacade()->hasTranslation($id, $localeTransfer)) {
            $id = $this->getFacade()->translate($id, $parameters, $localeTransfer);
        }

        return $id;
    }

    /**
     * {@inheritDoc}
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
    public function transChoice($identifier, $number, array $parameters = [], ?string $domain = null, ?string $locale = null)
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getLocale()
    {
        return $this->localeName;
    }

    /**
     * {@inheritDoc}
     *
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
            if ($this->localeName === null) {
                throw new InvalidArgumentException('No $localeTransfer or $localeName specified. You need to set one, otherwise translation can not properly work.');
            }
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->setLocaleName($this->localeName);

            $this->localeTransfer = $localeTransfer;
        }

        return $this->localeTransfer;
    }
}
