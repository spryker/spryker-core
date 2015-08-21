<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library;

use Symfony\Component\HttpFoundation\Request;

class Form extends \Zend_Form
{

    /**
     * @var \SprykerFeature_Zed_Library_Gui_Form_DataSource_Interface
     */
    protected $dataSource;

    /**
     * @param null $dataSource
     * @param Request $request
     */
    public function __construct($dataSource = null, Request $request = null)
    {
        if (!is_null($dataSource)) {
            $this->dataSource = $dataSource;
        }
        if (!is_null($request)) {
            $this->request = $request;
        }

        $this->setView(new \Zend_View());
        parent::__construct();
        $this->populateWithDataSource();
    }

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param array $data
     *
     * @return bool
     */
    public function isValid($data = null)
    {
        if (null === $data) {
            $data = $this->getRequest()->request->all();
        }

        return parent::isValid($data);
    }

    /**
     * @return Request
     */
    protected function getRequest()
    {
        if (null === $this->request) {
            $this->request = Request::createFromGlobals();
        }

        return $this->request;
    }

    public function populateWithDataSource()
    {
        if ($this->dataSource) {
            $data = $this->dataSource->getData();
            if ($data) {
                $this->populate($data);
            }
        }
    }

    /**
     * @param \Zend_View_Interface $view
     *
     * @return string
     */
    public function render(\Zend_View_Interface $view = null)
    {
        if (null !== $view) {
            $this->setView($view);
        }

        $content = '';
        /** @var \Zend_Form_Decorator_Abstract $decorator */
        foreach ($this->getDecorators() as $decorator) {
            try {
                $decorator->setElement($this);
                $content = $decorator->render($content);
            } catch (\Exception $e) {
                $foo = 'bar';
            }
        }
        $this->_setIsRendered();

        return $content;
    }

}
