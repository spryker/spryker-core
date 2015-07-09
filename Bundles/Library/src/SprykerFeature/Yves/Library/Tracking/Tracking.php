<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Library\Tracking;

use SprykerFeature\Yves\Library\Tracking\DataProvider\DataProviderInterface;
use SprykerFeature\Yves\Library\Tracking\Provider\ProviderInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class Tracking
{

    const POSITION_BEFORE_CLOSING_HEAD = 'before closing head';
    const POSITION_AFTER_OPENING_BODY = 'after opening body';

    /**
     * @var
     */
    protected static $instance;

    /**
     * @var ProviderInterface[]
     */
    protected $provider = [];

    /**
     * @var DataProviderInterface[]
     */
    protected $dataProvider = [];

    /**
     * @var string
     */
    protected $pageType;

    /**
     * @var array
     */
    protected $values = [];

    /**
     * @var array
     */
    protected $tracking = [];

    /**
     * @var Session
     */
    protected $session;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return Tracking
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param Session $session
     *
     * @return $this
     */
    public function setSession(Session $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setValue($key, $value)
    {
        $this->session->set($key, $value);

        return $this;
    }

    /**
     * @param $key
     * @param string $defaultValue
     *
     * @return mixed|string
     */
    public function getValue($key, $defaultValue = '')
    {
        if (!$this->session->has($key)) {
            return $defaultValue;
        }
        $value = $this->session->get($key);
        $this->session->remove($key);

        return $value;
    }

    public function reset()
    {
        self::$instance = null;
    }

    /**
     * @param ProviderInterface $provider
     *
     * @return $this
     */
    public function addProvider(ProviderInterface $provider)
    {
        $this->provider[] = $provider;

        return $this;
    }

    /**
     * @param DataProviderInterface $dataProvider
     *
     * @return $this
     */
    public function addDataProvider(DataProviderInterface $dataProvider)
    {
        if (array_key_exists($dataProvider->getProviderName(), $this->dataProvider)) {
            $dataProvider->mergeDataProvider($this->dataProvider[$dataProvider->getProviderName()]);
        }
        $this->dataProvider[$dataProvider->getProviderName()] = $dataProvider;

        return $this;
    }

    /**
     * @return $this
     */
    public function buildTracking()
    {
        /** @var ProviderInterface $provider */
        foreach ($this->provider as $provider) {
            $tracking = $this->createTrackingOutput(
                $provider->getTrackingOutput($this->dataProvider, $this->getPageType())
            );
            $this->tracking[$provider->getPosition()][] = $tracking;
        }

        return $this;
    }

    /**
     * @param $position
     *
     * @return mixed
     */
    public function getTrackingOutput($position)
    {
        if (array_key_exists($position, $this->tracking)) {
            return implode(PHP_EOL, $this->tracking[$position]);
        }
    }

    /**
     * @param $trackingOutput
     *
     * @return string
     */
    protected function createTrackingOutput($trackingOutput)
    {
        if ($this->isActive()) {
            return $trackingOutput;
        } else {
            return '<!-- ' . str_replace(['<!--', '-->'], '||', $trackingOutput) . ' -->';
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        $trackingData = [];
        foreach ($this->dataProvider as $provider) {
            $trackingData[$provider->getProviderName()] = $provider->getData();
        }

        return $trackingData;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        if (\SprykerFeature_Shared_Library_Environment::isProduction() || \SprykerFeature_Shared_Library_Environment::isStaging() || \SprykerFeature_Shared_Library_Environment::isTesting()) {
            return true;
        }

        return false;
    }

    /**
     * @param $pageType
     *
     * @return $this
     */
    public function setPageType($pageType)
    {
        $this->pageType = $pageType;

        return $this;
    }

    /**
     * @return string
     */
    public function getPageType()
    {
        if ($this->pageType) {
            return $this->pageType;
        } else {
            return PageTypeInterface::PAGE_TYPE_HOME;
        }
    }

}
