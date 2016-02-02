<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Business\Manager;

use Spryker\Zed\Locale\Business\Exception\LocaleExistsException;
use Spryker\Zed\Locale\Business\Exception\MissingLocaleException;
use Spryker\Zed\Locale\Business\TransferGeneratorInterface;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface;
use Orm\Zed\Locale\Persistence\SpyLocale;

class LocaleManager
{

    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface
     */
    protected $localeQueryContainer;

    /**
     * @var \Spryker\Zed\Locale\Business\TransferGeneratorInterface
     */
    protected $transferGenerator;

    /**
     * @param \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface $localeQueryContainer
     * @param \Spryker\Zed\Locale\Business\TransferGeneratorInterface $transferGenerator
     */
    public function __construct(
        LocaleQueryContainerInterface $localeQueryContainer,
        TransferGeneratorInterface $transferGenerator
    ) {
        $this->localeQueryContainer = $localeQueryContainer;
        $this->transferGenerator = $transferGenerator;
    }

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale($localeName)
    {
        $localeQuery = $this->localeQueryContainer->queryLocaleByName($localeName);
        $locale = $localeQuery->findOne();
        if (!$locale) {
            throw new MissingLocaleException(
                sprintf(
                    'Tried to retrieve locale %s, but it does not exist',
                    $localeName
                )
            );
        }

        return $this->transferGenerator->convertLocale($locale);
    }

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\LocaleExistsException
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function createLocale($localeName)
    {
        if ($this->hasLocale($localeName)) {
            throw new LocaleExistsException(
                sprintf(
                    'Tried to create locale %s, but it already exists',
                    $localeName
                )
            );
        }

        $locale = new SpyLocale();
        $locale->setLocaleName($localeName);

        $locale->save();

        return $this->transferGenerator->convertLocale($locale);
    }

    /**
     * @param string $localeName
     *
     * @return bool
     */
    public function hasLocale($localeName)
    {
        $localeQuery = $this->localeQueryContainer->queryLocaleByName($localeName);

        return $localeQuery->count() > 0;
    }

    /**
     * @param string $localeName
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function deleteLocale($localeName)
    {
        if (!$this->hasLocale($localeName)) {
            return true;
        }

        $locale = $this->localeQueryContainer
            ->queryLocaleByName($localeName)
            ->findOne();

        $locale->setIsActive(false);
        $locale->save();

        return true;
    }

}
