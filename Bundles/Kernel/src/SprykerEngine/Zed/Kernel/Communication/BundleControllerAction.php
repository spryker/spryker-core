<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Shared\Kernel\Communication\BundleControllerActionInterface;
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
     * @param Request $request
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
     * @return DashToCamelCase
     */
    private function getFilter()
    {
        if (is_null($this->filter)) {
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
