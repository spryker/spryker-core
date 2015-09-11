<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request\Partial;

use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequestExporter;

class Header extends AbstractRequestExporter
{
    /**
     * @var Security
     */
    protected $security;

    public function __construct()
    {
        $this->setSecurity(new Security());
    }

    /**
     * @return Security
     */
    public function getSecurity()
    {
        return $this->security;
    }

    /**
     * @param Security $security
     */
    public function setSecurity($security)
    {
        $this->security = $security;
    }
}
