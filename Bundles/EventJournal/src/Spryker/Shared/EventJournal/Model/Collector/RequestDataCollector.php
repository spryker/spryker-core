<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventJournal\Model\Collector;

use Spryker\Service\UtilText\UtilTextService;

/**
 * @deprecated Use Log bundle instead
 */
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

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);

        if (self::$idRequest === null) {
            self::$idRequest = $this->getRandomString();
        }
    }

    /**
     * @return string
     */
    protected function getRandomString()
    {
        $utilTextService = new UtilTextService();

        return $utilTextService->generateRandomString(32);
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
