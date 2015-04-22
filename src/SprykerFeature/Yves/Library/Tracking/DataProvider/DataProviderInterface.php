<?php

namespace SprykerFeature\Yves\Library\Tracking\DataProvider;

interface DataProviderInterface
{

    /**
     * @return string
     */
    public function getProviderName();

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function addData($key, $value);

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function setData(array $data);

    /**
     * @return array
     */
    public function getData();

    /**
     * @param DataProviderInterface $dataProvider
     *
     * @return self
     *
     * @throws \SprykerFeature\Yves\Library\Tracking\DataProvider\DataProviderException
     */
    public function mergeDataProvider(DataProviderInterface $dataProvider);
}
