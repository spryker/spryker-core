<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface;

class LocaleProvider implements LocaleProviderInterface
{
    public const DEFAULT_LOCALE = 'default';
    /**
     * @var \Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface $localeFacade
     */
    public function __construct(CategoryImageGuiToLocaleInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param bool $includeDefault
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleCollection($includeDefault = false): array
    {
        $result = [];

        if ($includeDefault) {
            $result[] = (new LocaleTransfer())
                ->setLocaleName(static::DEFAULT_LOCALE);
        }

        foreach ($this->localeFacade->getLocaleCollection() as $localeCode => $localeTransfer) {
            $result[] = $localeTransfer;
        }

        return $result;
    }

    /**
     * @param string $localeCode
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleTransfer($localeCode): LocaleTransfer
    {
        return $this->localeFacade->getLocale($localeCode);
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale(): LocaleTransfer
    {
        return $this->localeFacade->getCurrentLocale();
    }

    /**
     * {@inheritdoc}
     */
    public function hasLocale(string $localeName): bool
    {
        return $this->localeFacade->hasLocale($localeName);
    }

    /**
     * {@inheritdoc}
     */
    public function createDefaultLocale(): LocaleTransfer
    {
        return (new LocaleTransfer())
            ->setLocaleName(static::DEFAULT_LOCALE);
    }
}
