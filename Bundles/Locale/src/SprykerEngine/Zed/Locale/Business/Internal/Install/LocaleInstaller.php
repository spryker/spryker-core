<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Locale\Business\Internal\Install;

use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Locale\Persistence\LocaleQueryContainerInterface;
use Orm\Zed\Locale\Persistence\SpyLocale;

class LocaleInstaller extends AbstractInstaller
{

    /**
     * @var string
     */
    protected $localeFile;

    /**
     * @var LocaleQueryContainerInterface
     */
    protected $localeQueryContainer;

    /**
     * @param LocaleQueryContainerInterface $localeQueryContainer
     * @param string $localeFile
     */
    public function __construct(LocaleQueryContainerInterface $localeQueryContainer, $localeFile)
    {
        $this->localeFile = $localeFile;
        $this->localeQueryContainer = $localeQueryContainer;
    }

    /**
     */
    public function install()
    {
        $this->installLocales();
    }

    /**
     * @throws PropelException
     */
    protected function installLocales()
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
