<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Gui\Communication\Form;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Application\Communication\Application;
use SprykerEngine\Zed\Kernel\Locator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormDisabled
{

    const FORM_FACTORY = 'form.factory';

    /**
     * This must be set from the outside (e.g. via constructor injection)
     *
     * @var FormTypeInterface
     */
    protected $formType;

    /**
     * @param FormTypeInterface|null $formType
     */
    public function __construct(FormTypeInterface $formType)
    {
        $this->formType = $formType;
    }

    /**
     * @return array
     */
    abstract protected function populateFormFields();

    /**
     * @throws \ErrorException
     *
     * @return FormInterface
     */
    public function create()
    {
        if ($this->formType === null) {
            throw new \ErrorException('You need to initialize $this->formType which extends SprykerFeature\Zed\Gui\Communication\Form\AbstractFormType'); // TODO Exception type
        }

        $this->init();

        $populatedData = $this->populateFormFields();

        return $this->getApplication()[self::FORM_FACTORY]
            ->create($this->formType, $populatedData)
        ;
    }

    /**
     * @return void
     */
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
