<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Twig;

abstract class TwigFilter extends \Twig_SimpleFilter
{

    public function __construct()
    {
        parent::__construct($this->getFilterName(), $this->getFunction(), $this->getOptions());
    }

    /**
     * @return string
     */
    abstract protected function getFilterName();

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
