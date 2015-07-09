<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Twig;

abstract class TwigFunction extends \Twig_SimpleFunction
{

    public function __construct()
    {
        parent::__construct($this->getFunctionName(), $this->getFunction(), $this->getOptions());
    }

    /**
     * @return string
     */
    abstract protected function getFunctionName();

    /**
     * @return callable
     */
    abstract protected function getFunction();

    /**
     * @return array
     */
    protected function getOptions()
    {
        return ['is_safe' => ['html']];
    }

}
