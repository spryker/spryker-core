<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Library\Tracking\DataProvider;

abstract class AbstractDataProvider implements DataProviderInterface
{

    /**
     * @var array
     */
    protected $trackingData = [];

    /**
     * @return string
     */
    public function getProviderName()
    {
        return static::DATA_PROVIDER_NAME;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return self
     */
    public function addData($key, $value)
    {
        $this->trackingData[$key] = $value;

        return $this;
    }

    /**
     * @param array $trackingData
     *
     * @return self
     */
    public function setData(array $trackingData)
    {
        $this->trackingData = $trackingData;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->trackingData;
    }

    /**
     * @param $key
     * @param string $defaultValue
     *
     * @return self
     */
    public function getValue($key, $defaultValue = '')
    {
        if (array_key_exists($key, $this->trackingData)) {
            return $this->trackingData[$key];
        }

        return $defaultValue;
    }

    /**
     * @param DataProviderInterface $dataProvider
     *
     * @return self
     */
    public function mergeDataProvider(DataProviderInterface $dataProvider)
    {
        $this->canMerge($dataProvider);
        $trackingData = array_merge(
            $this->getData(),
            $dataProvider->getData()
        );
        $this->setData($trackingData);

        return $this;
    }

    /**
     * @param DataProviderInterface $dataProvider
     *
     * @throws DataProviderException
     */
    protected function canMerge(DataProviderInterface $dataProvider)
    {
        if ($dataProvider->getProviderName() !== $this->getProviderName()) {
            throw new DataProviderException(
                'It\'s not allowed to merge different data provider! Tried to merge "' . $this->getProviderName() . '" with "' . $dataProvider->getProviderName() . '".'
            );
        }
    }

}
