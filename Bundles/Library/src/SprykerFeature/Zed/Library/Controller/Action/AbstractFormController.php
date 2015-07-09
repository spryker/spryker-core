<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Controller\Action;

use SprykerFeature\Zed\Library\Form as FormForm;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormController extends AbstractWidgetController
{

    /**
     * @var FormForm
     */
    protected $form;

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $this->initialize($request);

        return $this->renderFormWidget($request);
    }

    /**
     * @param Request $request
     *
     * @return mixed|void
     */
    protected function initialize(Request $request)
    {
        $this->form = $this->initializeForm($request);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    abstract protected function initializeForm(Request $request);

    /**
     * @return array
     */
    protected function renderFormWidget()
    {
        return $this->renderWidget('form', $this->form);
    }

}
