<?php

namespace SprykerFeature\Zed\Payone\Business\Api\Adapter\Http;


class Simulator extends AbstractHttpAdapter
{

    const SIMULATION_MODE_APPROVED = 'simulation_mode_approved';
    const SIMULATION_MODE_ERROR = 'simulation_mode_error';
    const SIMULATION_MODE_VALID = 'simulation_mode_valid';
    const SIMULATION_MODE_REDIRECT = 'simulation_mode_redirect';

    /**
     * @var string
     */
    protected $simulationMode;


    /**
     * @param string $simulationMode
     */
    public function setSimulationMode($simulationMode)
    {
        $this->simulationMode = $simulationMode;
    }

    /**
     * @return array
     */
    protected function getSimulationModes()
    {
        $modes = [];
        $modes[] = self::SIMULATION_MODE_APPROVED;
        $modes[] = self::SIMULATION_MODE_ERROR;
        $modes[] = self::SIMULATION_MODE_VALID;
        $modes[] = self::SIMULATION_MODE_REDIRECT;
        return $modes;
    }

    /**
     * @param $simulationMode
     * @return bool
     */
    protected function isValidSimulationMode($simulationMode)
    {
        return in_array($simulationMode, $this->getSimulationModes());
    }

    /**
     * @param $simulationMode
     * @return string
     * @throws \ErrorException
     */
    protected function getSimulationResponse($simulationMode)
    {
        if(!$this->isValidSimulationMode($simulationMode)) {
            throw new \ErrorException('Not a valid payone simulation mode: ' . $simulationMode);
        }
        switch($simulationMode) {
            case self::SIMULATION_MODE_APPROVED :
                return $this->getSimulateApprovedResponse();
                break;
            case self::SIMULATION_MODE_ERROR :
                return $this->getSimulateErrorResponse();
                break;
            case self::SIMULATION_MODE_VALID :
                return $this->getSimulateValidResponse();
                break;
            case self::SIMULATION_MODE_REDIRECT :
                return $this->getSimulateRedirectResponse();
                break;
        }
    }

    /**
     * @param array $params
     * @return array
     * @throws \ErrorException
     */
    protected function performRequest(array $params)
    {
        $result = $this->getSimulationResponse($this->simulationMode);
        $this->setRawResponse($result);
        $response = explode("\n", $result);

        return $response;
    }

    /**
     * @return string
     */
    protected function getSimulateApprovedResponse()
    {
        return 'status=APPROVED
                txid=12345678
                userid=44455566';
    }

    /**
     * @return string
     */
    protected function getSimulateErrorResponse()
    {
        return 'status=ERROR
                errorcode=877
                errormessage=Invalid card number (LUHN check failed)
                customermessage=Invalid card number. Please verify your card data.';
    }

    /**
     * @return string
     */
    protected function getSimulateValidResponse()
    {
        return 'status=VALID';
    }

    /**
     * @return string
     */
    protected function getSimulateRedirectResponse()
    {
        return 'status=REDIRECT
                txid=12345678
                userid=44455566
                redirecturl=https://www.payone.com/pay/';
    }

    /**
     * @return string
     */
    public function getUrl()
    {

    }

}
