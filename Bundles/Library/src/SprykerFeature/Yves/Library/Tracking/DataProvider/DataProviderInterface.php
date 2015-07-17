<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

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
     * @throws \SprykerFeature\Yves\Library\Tracking\DataProvider\DataProviderException
     *
     * @return self
     */
    public function mergeDataProvider(DataProviderInterface $dataProvider);

}
