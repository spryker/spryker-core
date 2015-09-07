<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model\Collector;

use Symfony\Component\HttpFoundation\Request;

class RequestDataCollector extends AbstractDataCollector
{

    /**
     * @var string
     */
    static $idRequest;

    public function __construct(array $options)
    {
        parent::__construct($options);

        $this->setDefaultOptions();

        if (null === self::$idRequest) {
            self::$idRequest = uniqid('', true);
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        $fields = [
            'request_id'     => self::$idRequest,
            'microtime'      => microtime(true),
            'request_params' => $this->getRequestParams(),
        ];
        $fields = array_merge($fields, $this->getModuleControllerAction());

        return $fields;
    }

    protected function getRequestParams()
    {
        return $this->applyBlackList($_REQUEST);
    }

    protected function applyBlackList($requestParams)
    {
        foreach ($requestParams as $name => &$value) {
            if (in_array($name, $this->options['param_blacklist'])) {
                $value = $this->options['filtered_content'];
            }
        }

        return $requestParams;
    }

    /**
     * @return array
     */
    protected function getModuleControllerAction()
    {
        $request = Request::createFromGlobals();

        return [
            'route'      => $this->getRoute($request),
            'module'     => $request->attributes->get('module'),
            'controller' => $request->attributes->get('controller'),
            'action'     => $request->attributes->get('action'),
        ];
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    protected function getRoute(Request $request)
    {
        return sprintf("%s/%s/%s",
            $request->attributes->get('module'),
            $request->attributes->get('controller'),
            $request->attributes->get('action')
        );
    }

    protected function setDefaultOptions()
    {
        if (!isset($this->options['param_blacklist'])) {
            $this->options['param_blacklist'] = [];
        }

        if (!isset($this->options['filtered_content'])) {
            $this->options['filtered_content'] = '***FILTERED***';
        }
    }
}
