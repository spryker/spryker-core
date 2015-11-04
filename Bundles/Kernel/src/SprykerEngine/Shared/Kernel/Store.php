<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerFeature\Shared\Application\ApplicationConfig;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\NewRelic\Api;
use SprykerFeature\Shared\System\SystemConfig;

class Store
{

    /**
     * @var Store
     */
    protected static $instance;

    /**
     * Name of the store = name of the area
     *
     * @link  https://project-a.atlassian.net/wiki/display/SYSOP/Port+numbering
     *
     * @var string
     */
    protected $storeName;

    /**
     * List of all storeNames
     *
     * @var array
     */
    protected $allStoreNames;

    /**
     * List of locales
     *
     * @var array
     */
    protected $locales;

    /**
     * List of countries
     *
     * @var array
     */
    protected $countries;

    /**
     * Examples: DE, PL
     *
     * @var string
     */
    protected $currentCountry;

    /**
     * Examples: de_DE, pl_PL
     *
     * @var string
     */
    protected $currentLocale;

    /**
     * Examples: EUR, PLN
     *
     * @link http://en.wikipedia.org/wiki/ISO_4217
     *
     * @var string
     */
    protected $currencyIsoCode;

    /**
     * @var array
     */
    protected $contexts;

    /**
     * @var string
     */
    protected static $defaultStore;

    /**
     * @return Store
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return string
     */
    public static function getDefaultStore()
    {
        if (is_null(self::$defaultStore)) {
            self::$defaultStore = require APPLICATION_ROOT_DIR . '/config/Shared/default_store.php';
        }

        return self::$defaultStore;
    }

    protected function __construct()
    {
        $currentStoreName = APPLICATION_STORE;
        $this->initializeSetup($currentStoreName);
        $this->publish();
    }

    protected function publish()
    {
        header('X-Locale: ' . $this->getCurrentLocale());
        header('X-Store: ' . $this->getStoreName());
        header('X-Env: ' . APPLICATION_ENV);

        $newRelicApi = new Api();
        $newRelicApi->addCustomParameter('locale', $this->getCurrentLocale());
        $newRelicApi->addCustomParameter('store', $this->getStoreName());
    }

    /**
     * @param string $currentStoreName
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function getStoreSetup($currentStoreName)
    {
        $stores = require APPLICATION_ROOT_DIR . '/config/Shared/stores.php';

        if (false === array_key_exists($currentStoreName, $stores)) {
            throw new \Exception('Missing setup for store: ' . $currentStoreName);
        }

        return $stores;
    }

    /**
     * @param string $currentStoreName
     *
     * @throws \Exception
     */
    public function initializeSetup($currentStoreName)
    {
        $stores = $this->getStoreSetup($currentStoreName);
        $storeArray = $stores[$currentStoreName];

        $vars = get_object_vars($this);
        foreach ($storeArray as $k => $v) {
            if (array_key_exists($k, $vars)) {
                $this->$k = $v;
            } else {
                // bc
                if ($k === 'frontends') {
                    continue;
                }
                throw new \Exception('Unknown setup-key: ' . $k);
            }
        }

        $this->storeName = $currentStoreName;
        $this->allStoreNames = array_keys($stores);
        $this->setCurrentLocale(current($this->locales));
        $this->setCurrentCountry(current($this->countries));

        foreach ($vars as $k => $v) {
            if (empty($this->$k)) {
                throw new \Exception('Missing setup-key: ' . $k);
            }
        }
    }

    /**
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    /**
     * @param string $locale string The locale, e.g. 'de_DE'
     *
     * @return string The language, e.g. 'de'
     */
    protected function getLanguageFromLocale($locale)
    {
        //TODO use strstr here
        return substr($locale, 0, strpos($locale, '_'));
    }

    /**
     * @return string
     */
    public function getCurrentLanguage()
    {
        return $this->getLanguageFromLocale($this->currentLocale);
    }

    /**
     * @return array
     */
    public function getAllowedStores()
    {
        return $this->allStoreNames;
    }

    /**
     * @return array
     */
    public function getInactiveStores()
    {
        $inActiveStores = [];
        foreach ($this->getAllowedStores() as $store) {
            if ($this->storeName !== $store) {
                $inActiveStores[] = $store;
            }
        }

        return $inActiveStores;
    }

    /**
     * @return string
     */
    public function getCurrencyIsoCode()
    {
        return $this->currencyIsoCode;
    }

    /**
     * @return array
     */
    public function getLocales()
    {
        return $this->locales;
    }

    /**
     * @return string
     */
    public function getStoreName()
    {
        return $this->storeName;
    }

    /**
     * @param string $storeName
     *
     * @return $this
     */
    public function setStoreName($storeName)
    {
        $this->storeName = $storeName;

        return $this;
    }

    /**
     * @param string $currentLocale
     */
    public function setCurrentLocale($currentLocale)
    {
        $this->currentLocale = $currentLocale;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return Config::get(SystemConfig::PROJECT_TIMEZONE);
    }

    /**
     * @return array
     */
    public function getContexts()
    {
        return $this->contexts;
    }

    /**
     * @return array
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * @param string $currentCountry
     */
    public function setCurrentCountry($currentCountry)
    {
        $this->currentCountry = $currentCountry;
    }

    /**
     * @return string
     */
    public function getCurrentCountry()
    {
        return $this->currentCountry;
    }

    /**
     * @return string
     */
    public function getStorePrefix()
    {
        $prefix = Config::get(ApplicationConfig::STORE_PREFIX, '');
        $prefix .= $this->getStoreName();

        return $prefix;
    }

}
