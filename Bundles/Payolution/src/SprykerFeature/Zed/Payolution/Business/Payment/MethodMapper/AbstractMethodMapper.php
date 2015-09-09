<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Header;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Identification;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Payment;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Presentation;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Security;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Transaction;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\User;
use SprykerFeature\Zed\Payolution\Business\Payment\MethodMapperInterface;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;

abstract class AbstractMethodMapper implements MethodMapperInterface
{

    /**
     * @var PayolutionConfig
     */
    private $config;

    public function __construct(PayolutionConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return PayolutionConfig
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Header
     */
    protected function getHeaderPartialRequest()
    {
        $security = new Security();
        $security->setSender($this->getConfig()->getSecuritySender());

        $header = new Header();
        $header->setSecurity($security);
        return $header;
    }

    /**
     * @return User
     */
    protected function getUserPartialRequest()
    {
        $user = new User();
        $user->setLogin($this->getConfig()->getUserLogin());
        $user->setPwd($this->getConfig()->getUserPassword());
        return $user;
    }
}
