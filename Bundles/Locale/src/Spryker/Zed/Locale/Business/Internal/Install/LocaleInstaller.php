<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business\Internal\Install;

use Orm\Zed\Locale\Persistence\SpyLocale;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface;

class LocaleInstaller implements LocaleInstallerInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected $localeFile;

    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface
     */
    protected $localeQueryContainer;

    /**
     * @param \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface $localeQueryContainer
     * @param string $localeFile
     */
    public function __construct(LocaleQueryContainerInterface $localeQueryContainer, $localeFile)
    {
        $this->localeFile = $localeFile;
        $this->localeQueryContainer = $localeQueryContainer;
    }

    /**
     * @return void
     */
    public function install()
    {
        $this->installLocales();
    }

    /**
     * @return void
     */
    protected function installLocales()
    {
        $this->getTransactionHandler()->handleTransaction(function (): void {
            $this->executeInstallLocalesTransaction();
        });
    }

    /**
     * @return void
     */
    protected function executeInstallLocalesTransaction(): void
    {
        $localeFile = fopen($this->localeFile, 'r');

        while (!feof($localeFile)) {
            $locale = trim(fgets($localeFile));

            $query = $this->localeQueryContainer->queryLocaleByName($locale);

            if (!$query->count()) {
                $entity = new SpyLocale();
                $entity->setLocaleName($locale);
                $entity->setIsActive(1);
                $entity->save();
            }
        }
    }
}
