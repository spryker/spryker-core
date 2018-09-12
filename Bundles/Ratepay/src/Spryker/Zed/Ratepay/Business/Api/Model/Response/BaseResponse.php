<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Response;

use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Api\SimpleXMLElement;

class BaseResponse implements ResponseInterface
{
    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\SimpleXMLElement
     */
    protected $xmlObject;

    /**
     * @var array
     */
    protected $successMatrix = Constants::REQUEST_CODE_SUCCESS_MATRIX;

    /**
     * @param string $xmlString
     */
    public function __construct($xmlString)
    {
        $this->xmlObject = new SimpleXMLElement($xmlString);
    }

    /**
     * @return string
     */
    public function getCustomerMessage()
    {
        return (string)$this->xmlObject->head->processing->{'customer-message'};
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return (string)$this->xmlObject->content->payment['method'];
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        $operation = (string)$this->xmlObject->head->operation;
        return $this->successMatrix[$operation] === $this->getResultCode();
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return (string)$this->xmlObject->head->processing->status['code'];
    }

    /**
     * @return int
     */
    public function getReasonCode()
    {
        return (int)$this->xmlObject->head->processing->reason['code'];
    }

    /**
     * @return int
     */
    public function getResultCode()
    {
        return (int)$this->xmlObject->head->processing->result['code'];
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return (string)$this->xmlObject->head->{'transaction-id'};
    }

    /**
     * @return string
     */
    public function getTransactionShortId()
    {
        return (string)$this->xmlObject->head->{'transaction-short-id'};
    }

    /**
     * @return string
     */
    public function getResponseType()
    {
        return (string)$this->xmlObject->head->{'response-type'};
    }

    /**
     * @return string
     */
    public function getReasonText()
    {
        return (string)$this->xmlObject->head->processing->reason;
    }

    /**
     * @return string
     */
    public function getResultText()
    {
        return (string)$this->xmlObject->head->processing->result;
    }

    /**
     * @return string
     */
    public function getStatusText()
    {
        return (string)$this->xmlObject->head->processing->status;
    }
}
