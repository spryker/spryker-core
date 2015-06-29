<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Response\Container;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer;


class GetInvoiceResponseContainer extends AbstractResponseContainer
{

    /**
     * @var string
     */
    protected $response;


    /**
     * @param string $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->isError()) {
            $result = parent::__toString();
        }
        else {
            $stringArray = array('status=' . $this->getStatus(), 'data=PDF-Content');
            $result = implode('|', $stringArray);
        }
        return $result;
    }

}
