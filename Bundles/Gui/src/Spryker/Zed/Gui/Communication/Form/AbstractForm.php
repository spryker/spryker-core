<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Form;

use Spryker\Shared\Gui\Form\AbstractForm as SharedAbstractForm;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use Spryker\Zed\Gui\Communication\Plugin\ConstraintsPlugin;

/**
 * @deprecated Use {@link \Spryker\Zed\Kernel\Communication\Form\AbstractType} for Zed instead.
 */
abstract class AbstractForm extends SharedAbstractForm
{
    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     */
    protected const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Spryker\Zed\Gui\Communication\Plugin\ConstraintsPlugin
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
     * @param \Symfony\Component\HttpFoundation\Request $request
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
            /** @var \Symfony\Component\HttpFoundation\RequestStack $requestStack */
            $requestStack = (new GlobalContainer())->get(static::SERVICE_REQUEST_STACK);
            $this->request = $requestStack->getCurrentRequest();
        }

        return $this->request;
    }
}
