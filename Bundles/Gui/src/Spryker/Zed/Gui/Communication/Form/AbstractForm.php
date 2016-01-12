<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Gui\Communication\Form;

use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Symfony\Component\HttpFoundation\Request;
use Spryker\Shared\Gui\Form\AbstractForm as SharedAbstractForm;

abstract class AbstractForm extends SharedAbstractForm
{

    /**
     * @var Request
     */
    protected $request;

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
     * @return Request
     */
    protected function getRequest()
    {
        if ($this->request === null) {
            $this->request = (new Pimple())->getApplication()['request'];
        }

        return $this->request;
    }

}
