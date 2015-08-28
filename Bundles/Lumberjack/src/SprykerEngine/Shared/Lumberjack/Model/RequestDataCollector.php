<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model;

use Symfony\Component\HttpFoundation\Request;

class RequestDataCollector implements DataCollectorInterface
{

    /**
     * @var string
     */
    static $idRequest;

    public function __construct()
    {
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
            'request_id' => self::$idRequest,
            'microtime'  => microtime(true),
        ];
        $fields = array_merge($fields, $this->getModuleControllerAction());

        return $fields;
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
}
