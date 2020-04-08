<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business\Reader;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Locale\Business\Cache\LocaleCacheInterface;
use Spryker\Zed\Locale\Business\Exception\MissingLocaleException;
use Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface;

class LocaleReader implements LocaleReaderInterface
{
    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface
     */
    protected $localeRepository;

    /**
     * @var \Spryker\Zed\Locale\Business\Cache\LocaleCacheInterface
     */
    protected $localeCache;

    /**
     * @param \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface $localeRepository
     * @param \Spryker\Zed\Locale\Business\Cache\LocaleCacheInterface $localeCache
     */
    public function __construct(LocaleRepositoryInterface $localeRepository, LocaleCacheInterface $localeCache)
    {
        $this->localeRepository = $localeRepository;
        $this->localeCache = $localeCache;
    }

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleByName(string $localeName): LocaleTransfer
    {
        $localeTransfer = $this->localeCache->findByName($localeName);
        if ($localeTransfer) {
            return $localeTransfer;
        }

        $localeTransfer = $this->localeRepository->findLocaleTransferByLocaleName($localeName);

        if ($localeTransfer) {
            $this->localeCache->set($localeTransfer);

            return $localeTransfer;
        }

        throw new MissingLocaleException(
            sprintf(
                'Tried to retrieve locale %s, but it does not exist',
                $localeName
            )
        );
    }
}
