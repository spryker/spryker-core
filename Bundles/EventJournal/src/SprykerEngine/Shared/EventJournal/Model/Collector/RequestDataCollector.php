<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\EventJournal\Model\Collector;

use Symfony\Component\HttpFoundation\Request;

class RequestDataCollector extends AbstractDataCollector
{

    const TYPE = 'request';

    const FIELD_REQUEST_ID = 'request_id';

    const FIELD_MICROTIME = 'microtime';

    const FIELD_REQUEST_PARAMS = 'request_params';

    /**
     * @var string
     */
    public static $idRequest;

    public function __construct(array $options)
    {
        parent::__construct($options);


        if (self::$idRequest === null) {
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

        return $fields;
    }

    /**
     * @return array
     */
    protected function getRequestParams()
    {
        return $_REQUEST;
    }
}
