<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business\Internal\Install;

use LogicException;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Locale\Persistence\LocaleEntityManagerInterface;
use Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface;

class LocaleInstaller implements LocaleInstallerInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected $localeFile;

    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface
     */
    protected $localeRepository;

    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleEntityManagerInterface
     */
    protected $localeEntityManager;

    /**
     * @param string $localeFile
     * @param \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface $localeRepository
     * @param \Spryker\Zed\Locale\Persistence\LocaleEntityManagerInterface $localeEntityManager
     */
    public function __construct(
        string $localeFile,
        LocaleRepositoryInterface $localeRepository,
        LocaleEntityManagerInterface $localeEntityManager
    ) {
        $this->localeFile = $localeFile;
        $this->localeRepository = $localeRepository;
        $this->localeEntityManager = $localeEntityManager;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $this->getTransactionHandler()->handleTransaction(function (): void {
            $this->executeInstallLocalesTransaction();
        });
    }

    /**
     * @throws \LogicException
     *
     * @return void
     */
    protected function executeInstallLocalesTransaction(): void
    {
        $localeFile = fopen($this->localeFile, 'r');

        if ($localeFile === false) {
            throw new LogicException('Could not open file: ' . $this->localeFile);
        }

        while (!feof($localeFile)) {
            $localeString = fgets($localeFile);

            if ($localeString === false) {
                throw new LogicException('Could not read from file: ' . $this->localeFile);
            }

            $locale = trim($localeString);

            $localesCount = $this->localeRepository->getLocalesCountByLocaleName($locale);

            if (!$localesCount) {
                $this->localeEntityManager->createLocale($locale);
            }
        }
    }
}
