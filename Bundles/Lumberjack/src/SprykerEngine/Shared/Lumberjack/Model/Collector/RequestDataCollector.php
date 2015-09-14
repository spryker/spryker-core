<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model\Collector;

use Symfony\Component\HttpFoundation\Request;

class RequestDataCollector extends AbstractDataCollector
{

    const TYPE = 'request';

    const FIELD_REQUEST_ID = 'request_id';

    const FIELD_MICROTIME = 'microtime';

    const FIELD_REQUEST_PARAMS = 'request_params';

    const FIELD_ROUTE = 'route';

    const FIELD_MODULE = 'module';

    const FIELD_CONTROLLER = 'controller';

    const FIELD_ACTION = 'action';

    const OPTION_PARAM_BLACKLIST = 'param_blacklist';

    const OPTION_FILTERED_CONTENT = 'filtered_content';

    /**
     * @var string
     */
    public static $idRequest;

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
            self::FIELD_REQUEST_ID => self::$idRequest,
            self::FIELD_MICROTIME => microtime(true),
            self::FIELD_REQUEST_PARAMS => $this->getRequestParams(),
        ];
        $fields = array_merge($fields, $this->getModuleControllerAction());

        return $fields;
    }

    /**
     * @return array
     */
    protected function getRequestParams()
    {
        return $this->applyBlackList($_REQUEST);
    }

    /**
     * @param $requestParams
     *
     * @return array
     */
    protected function applyBlackList(array $requestParams)
    {
        foreach ($requestParams as $name => &$value) {
            if (in_array($name, $this->options[self::OPTION_PARAM_BLACKLIST])) {
                $value = $this->options[self::OPTION_FILTERED_CONTENT];
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
            self::FIELD_ROUTE => $this->getRoute($request),
            self::FIELD_MODULE => $request->attributes->get(self::FIELD_MODULE),
            self::FIELD_CONTROLLER => $request->attributes->get(self::FIELD_CONTROLLER),
            self::FIELD_ACTION => $request->attributes->get(self::FIELD_ACTION),
        ];
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    protected function getRoute(Request $request)
    {
        return sprintf('%s/%s/%s',
            $request->attributes->get(self::FIELD_MODULE),
            $request->attributes->get(self::FIELD_CONTROLLER),
            $request->attributes->get(self::FIELD_ACTION)
        );
    }

    protected function setDefaultOptions()
    {
        if (!isset($this->options[self::OPTION_PARAM_BLACKLIST])) {
            $this->options[self::OPTION_PARAM_BLACKLIST] = [];
        }

        if (!isset($this->options[self::OPTION_FILTERED_CONTENT])) {
            $this->options[self::OPTION_FILTERED_CONTENT] = '***FILTERED***';
        }
    }

}
