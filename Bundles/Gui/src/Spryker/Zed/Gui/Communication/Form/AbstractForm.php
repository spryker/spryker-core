<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Gui\Communication\Form;

use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Spryker\Zed\Gui\Communication\Plugin\ConstraintsPlugin;
use Symfony\Component\HttpFoundation\Request;
use Spryker\Shared\Gui\Form\AbstractForm as SharedAbstractForm;

abstract class AbstractForm extends SharedAbstractForm
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ConstraintsPlugin
     */
    protected $constraintsPlugin;

    /**
     * @return \Spryker\Zed\Gui\Communication\Plugin\ConstraintsPlugin
     */
    public function getConstraints()
    {
        if ($this->constraintsPlugin === null) {
            $this->constraintsPlugin = new ConstraintsPlugin();
        }

        return $this->constraintsPlugin;
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        if ($this->request === null) {
            $this->request = (new Pimple())->getApplication()['request'];
        }

        return $this->request;
    }

}
