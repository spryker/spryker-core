<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Dependency\Form;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerFeature\Zed\Ui\Communication\Plugin\Form\Field;
use SprykerFeature\Zed\Ui\Communication\Plugin\Form\StateContainer\StateContainerInterface;
use SprykerFeature\Zed\Ui\Communication\Plugin\Form\SubForm;
use SprykerFeature\Zed\Ui\Dependency\Plugin\FormPluginInterface;
use Symfony\Component\HttpFoundation\Request;
use Exception;

/**
 * @deprecated use Gui AbstractForm
 */
abstract class AbstractForm
{

    const STATE_NEW = 'new';
    const STATE_SUCCESS = 'success';
    const STATE_FAIL = 'fail';

    const OUTPUT_STATE = 'state';
    const OUTPUT_FIELDS = 'fields';
    const OUTPUT_PAYLOAD = 'content';

    /**
     * @var AutoCompletion
     */
    private $locator;

    /**
     * @var AbstractQueryContainer
     */
    protected $queryContainer;

    /**
     * @var FormPluginInterface[]
     */
    protected $plugins;

    /**
     * @var StateContainerInterface
     */
    protected $stateContainer;

    /** @var bool */
    protected $isInitialized;

    /**
     * @return array
     * @deprecated use Gui AbstractForm
     */
    abstract protected function getDefaultData();

    /**
     * @deprecated use Gui AbstractForm
     */
    abstract public function addFormFields();

    /**
     * @param Request $request
     * @param QueryContainerInterface $queryContainer
     * @deprecated use Gui AbstractForm
     */
    public function __construct(
        Request $request = null,
        QueryContainerInterface $queryContainer = null
    ) {
        $this->locator = Locator::getInstance();

        if (is_null($request)) {
            $request = $this->locator->application()->pluginPimple()->getApplication()['request'];
        }

        $this->stateContainer = $this->locator->ui()->pluginFormStateContainerStateContainer()->setRequest($request);
        $this->queryContainer = $queryContainer;
    }

    /**
     * @deprecated use Gui AbstractForm
     */
    public function init()
    {
        $preparedValues = array_merge($this->getDefaultData(), $this->stateContainer->getRequestData());

        $this->stateContainer->setActiveValues($preparedValues);

        $this->addFormFields();

        $this->isInitialized = true;
    }

    /**
     * @return AutoCompletion
     * @deprecated use Gui AbstractForm
     */
    public function getLocator()
    {
        return $this->locator;
    }

    /**
     * @return $this|StateContainerInterface
     * @deprecated use Gui AbstractForm
     */
    public function getStateContainer()
    {
        return $this->stateContainer;
    }

    /**
     * @return bool
     * @deprecated use Gui AbstractForm
     */
    protected function isEveryPluginValid()
    {
        foreach ($this->plugins as $plugin) {
            if (!$plugin->isValid()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @throws Exception
     *
     * @return bool
     * @deprecated use Gui AbstractForm
     */
    public function isValid()
    {
        if (!$this->isInitialized) {
            throw new Exception('Form is not initialized.');
        }

        if (!$this->stateContainer->receivedSubmitRequest()) {
            return false;
        }

        return $this->isEveryPluginValid();
    }

    /**
     * @return string
     * @deprecated use Gui AbstractForm
     */
    protected function getState()
    {
        $state = self::STATE_NEW;
        if ($this->stateContainer->receivedSubmitRequest()) {
            $state = $this->isEveryPluginValid() ? self::STATE_SUCCESS : self::STATE_FAIL;
        }

        return $state;
    }

    /**
     * @throws Exception
     *
     * @return array|mixed
     * @deprecated use Gui AbstractForm
     */
    public function renderData()
    {
        return $this->renderDataForTwig();
    }

    /**
     * @return array
     * @deprecated use Gui AbstractForm
     */
    public function renderDataForTwig()
    {
        $data = $this->toArray();

        foreach ($data['fields'] as $k => $v) {
            $data['fields'][$v['name']] = $v;
            unset($data['fields'][$k]);
        }

        return $data['fields'];
    }

    /**
     * @throws Exception
     *
     * @return array|mixed
     * @deprecated use Gui AbstractForm
     */
    public function toArray()
    {
        if (!$this->isInitialized) {
            throw new Exception('Form is not initialized.');
        }

        $output = [];
        foreach ($this->plugins as $plugin) {
            $output = $plugin->extendOutput($output);
        }

        $output[self::OUTPUT_STATE] = $this->getState();

        return $output;
    }

    /**
     * @param $name
     *
     * @return Field
     * @deprecated use Gui AbstractForm
     */
    public function addField($name)
    {
        $field = $this->locator->ui()->pluginFormField();

        $field->setName($name);

        $field->setStateContainer($this->stateContainer);

        $this->plugins[$name] = $field;

        return $field;
    }

    /**
     * @param $name
     *
     * @return SubForm
     * @deprecated use Gui AbstractForm
     */
    public function addSubForm($name)
    {
        $subForm = $this->locator->ui()->pluginFormSubForm();

        $subForm->setName($name);

        $subForm->setStateContainer($this->stateContainer);

        $this->plugins[$name] = $subForm;

        return $subForm;
    }

    /**
     * @param $name
     *
     * @return SubForm
     * @deprecated use Gui AbstractForm
     */
    public function getSubFormByName($name)
    {
        return $this->plugins[$name];
    }

    /**
     * @param $name
     *
     * @return Field
     * @deprecated use Gui AbstractForm
     */
    public function getFieldByName($name)
    {
        return $this->plugins[$name];
    }

    /**
     * @return array|mixed
     * @deprecated use Gui AbstractForm
     */
    public function getRequestData()
    {
        return $this->stateContainer->getRequestData();
    }

    /**
     * @return array
     * @deprecated use Gui AbstractForm
     */
    public function setActiveValuesToDefault()
    {
        $defaultData = $this->getDefaultData();

        return $this->stateContainer->setActiveValues($defaultData);
    }

}
