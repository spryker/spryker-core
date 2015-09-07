<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request\Partial;


use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequest;

class Header extends AbstractRequest
{
    /**
     * @var string
     */
    protected $security;

    /**
     * @return string
     */
    public function getSecurity()
    {
        return $this->security;
    }

    /**
     * @param string $security
     */
    public function setSecurity($security)
    {
        $this->security = $security;
    }

}
