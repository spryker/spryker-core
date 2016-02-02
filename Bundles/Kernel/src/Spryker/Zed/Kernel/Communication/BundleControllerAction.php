<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Zend\Filter\Word\DashToCamelCase;

class BundleControllerAction implements BundleControllerActionInterface
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var DashToCamelCase
     */
    private $filter;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function filter($value)
    {
        return $this->getFilter()->filter($value);
    }

    /**
     * @return \Zend\Filter\Word\DashToCamelCase
     */
    private function getFilter()
    {
        if ($this->filter === null) {
            $this->filter = new DashToCamelCase();
        }

        return $this->filter;
    }

    /**
     * @return string
     */
    public function getBundle()
    {
        return $this->filter($this->request->attributes->get('module'));
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->filter($this->request->attributes->get('controller'));
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->filter($this->request->attributes->get('action'));
    }

}
