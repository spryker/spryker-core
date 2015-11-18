<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Gui\Communication\Form;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Application\Communication\Application;
use SprykerEngine\Zed\Kernel\Locator;
use Symfony\Component\HttpFoundation\Request;

/**
 */
abstract class AbstractForm
{
    const FORM_FACTORY = 'form.factory';

    /**
     * This must be set from the outside (e.g. via constructor injection)
     *
     * @var AbstractFormType
     */
    protected $formActionType;

    /**
     * Needs to be overwritten to populate the form
     * @return array
     */
    protected function populateFormFields()
    {
        return [];
    }

    /**
     * Move to abstract
     * @return mixed
     */
    public function create()
    {
        if ($this->formActionType === null) {
            throw new \ErrorException('You need to initialize $this->formType which extends SprykerFeature\Zed\Gui\Communication\Form\AbstractFormType'); // TODO Exception type
        }

        $this->init();

        $populatedData = $this->populateFormFields();

        return $this->getApplication()[self::FORM_FACTORY]->create($this->formActionType, $populatedData);
    }

    protected function init()
    {
        $this->request = $this->getRequest();
    }

    /**
     * @return AutoCompletion
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return Request
     */
    protected function getRequest()
    {
        return $this->getApplication()['request'];
    }

    /**
     * @return Application
     */
    private function getApplication()
    {
        return $this->getLocator()->application()->pluginPimple()->getApplication();
    }


}
