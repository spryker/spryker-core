<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request;

use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Header;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Transaction;

class PreAuthorizationRequest extends AbstractRequest
{

    /**
     * @var  Header
     */
    protected $header;

    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * @return Header
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param Header $header
     */
    public function setHeader(Header $header)
    {
        $this->header = $header;
    }

    /**
     * @return Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param Transaction $transaction
     */
    public function setTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

}
