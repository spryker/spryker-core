<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Provider;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocaleInterface;

class LocaleProvider implements LocaleProviderInterface
{
    public const DEFAULT_LOCALE = 'default';

    /**
     * @var \Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocaleInterface $localeFacade
     */
    public function __construct(CategoryImageToLocaleInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleCollection($includeDefault = false): array
    {
        $result = [];

        if ($includeDefault) {
            $result[] = $this->createDefaultLocale();
        }

        foreach ($this->localeFacade->getLocaleCollection() as $localeCode => $localeTransfer) {
            $result[] = $localeTransfer;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleTransfer($localeCode): LocaleTransfer
    {
        return $this->localeFacade->getLocale($localeCode);
    }

    /**
     * {@inheritdoc}
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
