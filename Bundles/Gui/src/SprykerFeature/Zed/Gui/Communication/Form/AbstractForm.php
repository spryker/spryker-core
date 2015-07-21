<?php

namespace SprykerFeature\Zed\Gui\Communication\Form;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Transfer\AbstractTransfer;
use SprykerEngine\Zed\Kernel\Locator;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Tests\Domain\AuditLoggerTest;
use SprykerFeature\Zed\Gui\Communication\Form\Type\SelectType;
use SprykerFeature\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraint;

/**
 * Class AbstractForm
 * @package SprykerFeature\Zed\Gui\Communication\Form
 */
abstract class AbstractForm
{
    /**
     * @var Form
     */
    protected $form = null;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var FormFactory
     */
    private $formFactory;

    protected $defaultDataType;


    /**
     * Prepares form
     *
     * @return $this
     */
    abstract protected function buildFormFields();

    /**
     * Set the values for fields
     *
     * @return $this
     */
    abstract protected function populateFormFields();

    /**
     * @return $this
     */
    protected function injectDependencies()
    {
        $app = $this->getLocator()
            ->application()
            ->pluginPimple()
            ->getApplication();

        $this->request = $app['request'];
        $this->formFactory = $app['form.factory'];

        $this->form = $this->formFactory->create('form', $this->getDefaultDataType());

        return $this;
    }

    public function setDefaultDataType($type=null)
    {
        $this->defaultDataType = $type;

        return $this;
    }

    protected function getDefaultDataType()
    {
        return $this->defaultDataType;
    }

    /**
     * @return AutoCompletion
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return $this
     */
    public function init()
    {
        $this->injectDependencies()
            ->buildFormFields();

        $data = $this->populateFormFields();
        $this->setData($data);

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->form->setData($data);

        return $this;
    }

    /**
     * @return mixed
     */
    public function createView()
    {
        return $this->form->createView();
    }

    /**
     * @return $this
     */
    public function handleRequest()
    {
        return $this->form->handleRequest($this->request);
    }

    /**
     * @return bool
     */
    public function isSubmitted()
    {
        return $this->form->isSubmitted();
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->form->isSubmitted() && $this->form->isValid();
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        $data = $this->form->getData();

        if (is_null($this->getDefaultDataType()) && $this->getDefaultDataType() instanceof AbstractTransfer) {
            return $this->getDefaultDataType()->fromArray($data, true);
        }

        return $data;
    }

    /**
     * @return FormErrorIterator
     */
    public function getErrors()
    {
        return $this->form->getErrors(true, false);
    }

    /**
     * @param string $name
     * @param string|Constraint $type
     * @param array $options
     *
     * @return $this
     */
    public function add($name, $type, $options = [])
    {
        return $this->form->add($name, $type, $options);
    }

    /**
     * @param string $name 
     * @param array $options
     *
     * @return $this
     */
    public function addText($name, $options = [])
    {
        $this->add($name, 'text', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addTextarea($name, $options = [])
    {
        $this->add($name, 'textarea', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addEmail($name, $options = [])
    {
        $this->add($name, 'email', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addInteger($name, $options = [])
    {
        $this->add($name, 'integer', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addMoney($name, $options = [])
    {
        $this->add($name, 'money', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addNumber($name, $options = [])
    {
        $this->add($name, 'number', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addPassword($name, $options = [])
    {
        $this->add($name, 'password', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addPercent($name, $options = [])
    {
        $this->add($name, 'percent', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addSearch($name, $options = [])
    {
        $this->add($name, 'search', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addUrl($name, $options = [])
    {
        $this->add($name, 'url', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addChoice($name, $options = [])
    {
        $this->add($name, 'choice', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addEntity($name, $options = [])
    {
        $this->add($name, 'entity', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addCountry($name, $options = [])
    {
        $this->add($name, 'country', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addLanguage($name, $options = [])
    {
        $this->add($name, 'language', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addLocale($name, $options = [])
    {
        $this->add($name, 'locale', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addTimezone($name, $options = [])
    {
        $this->add($name, 'timezone', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addCurrency($name, $options = [])
    {
        $this->add($name, 'currency', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addDate($name, $options = [])
    {
        $this->add($name, 'date', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addDatetime($name, $options = [])
    {
        $this->add($name, 'datetime', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addTime($name, $options = [])
    {
        $this->add($name, 'time', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addBirthday($name, $options = [])
    {
        $this->add($name, 'birthday', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addCheckbox($name, $options = [])
    {
        $this->add($name, 'checkbox', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addFile($name, $options = [])
    {
        $this->add($name, 'file', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addRadio($name, $options = [])
    {
        $this->add($name, 'radio', $options);

        return $this;
    }

    /* == Field Groups == */

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addCollection($name, $options = [])
    {
        $this->add($name, 'collection', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addRepeated($name, $options = [])
    {
        $this->add($name, 'repeated', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addHidden($name, $options = [])
    {
        $this->add($name, 'hidden', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addButton($name, $options = [])
    {
        $this->add($name, 'button', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addReset($name, $options = [])
    {
        $this->add($name, 'reset', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addSubmit($name = 'submit', $options = [])
    {
        $this->add($name, 'submit', $options);

        return $this;
    }

    /* == Base fields == */

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addForm($name, $options = [])
    {
        $this->add($name, 'form', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addSelect($name, $options = [])
    {
        $this->add($name, new SelectType(), $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addAutosuggest($name, $options = [])
    {
        $this->add($name, new AutosuggestType(), $options);

        return $this;
    }
}
