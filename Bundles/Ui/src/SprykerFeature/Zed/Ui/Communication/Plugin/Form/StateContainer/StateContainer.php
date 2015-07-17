<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Form\StateContainer;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

class StateContainer extends AbstractPlugin implements StateContainerInterface
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $activeData;

    /**
     * @var array
     */
    protected $requestData;

    /**
     * @return bool
     */
    public function receivedSubmitRequest()
    {
        return $this->request->isMethod('PUT');
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param $request
     *
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getRequestData()
    {
        $jsonRequestData = json_decode($this->request->getContent(), 1);

        if (!$jsonRequestData) {
            $jsonRequestData = $this->request->query->all();
        }

        if (!$jsonRequestData) {
            $jsonRequestData = [];
        }

        return $jsonRequestData;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    public function getRequestValue($key)
    {
        return $this->getValueOrNull(
            $this->getRequestData(),
            $key
        );
    }

    public function clearActiveData()
    {
        $this->activeData = [];
    }

    /**
     * @param $key
     *
     * @return mixed|void
     */
    public function clearActiveValue($key)
    {
        $this->activeData[$key] = null;
    }

    /**
     * @return array
     */
    public function getActiveValues()
    {
        return $this->activeData;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getActiveValue($key)
    {
        return $this->getValueOrNull(
            $this->activeData,
            $key
        );
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function setActiveValues(array $data)
    {
        $this->activeData = $data;

        return $this->activeData;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setActiveValue($key, $value)
    {
        $this->activeData[$key] = $value;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    public function getLatestValue($key)
    {
        $latestValue = $this->getRequestValue($key);

        if (!$latestValue) {
            $latestValue = $this->getActiveValue($key);
        }

        return $latestValue;
    }

    /**
     * @param array $data
     * @param $key
     *
     * @return null|mixed
     */
    protected function getValueOrNull(array $data, $key)
    {
        if (isset($data[$key])) {
            return $data[$key];
        }

        return;
    }

}
