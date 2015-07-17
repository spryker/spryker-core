<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Form\StateContainer;

use Symfony\Component\HttpFoundation\Request;

interface StateContainerInterface
{

    /**
     * @return bool
     */
    public function receivedSubmitRequest();

    /**
     * @return Request
     */
    public function getRequest();

    /**
     * @return array
     */
    public function getRequestData();

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getRequestValue($key);

    /**
     * @param array $data
     */
    public function setActiveValues(array $data);

    /**
     * @return array
     */
    public function getActiveValues();

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getActiveValue($key);

    /**
     * @param string $key
     * @param string $value
     */
    public function setActiveValue($key, $value);

    public function clearActiveData();

    /**
     * @param string $key
     */
    public function clearActiveValue($key);

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getLatestValue($key);

}
