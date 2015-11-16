<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Gui\Communication\Form;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Gui\Communication\Plugin\ConstraintsPlugin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormType extends AbstractType
{

    /**
     * @var ConstraintsPlugin
     */
    protected $constraintsPlugin;

    /**
     * @return ConstraintsPlugin
     */
    public function getConstraints()
    {
        if (is_null($this->constraintsPlugin)) {
            $this->constraintsPlugin = $this->getLocator()->gui()->pluginConstraintsPlugin();
        }

        return $this->constraintsPlugin;
    }

    /**
     * @return AutoCompletion
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public function getEnumSet(array $array)
    {
        return array_combine($array, $array);
    }

    /**
     * @return Request
     */
    protected function getRequest()
    {
        return $this->getLocator()->application()->pluginPimple()->getApplication()['request'];
    }
}
