<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Log\Processor;

use Spryker\Service\UtilEncoding\Model\Json;
use Spryker\Shared\Log\Sanitizer\SanitizerInterface;

class GuzzleBodyProcessor
{
    public const EXTRA = 'guzzle-body';
    public const RECORD_CONTEXT = 'context';
    public const RECORD_EXTRA = 'extra';

    /**
     * @var \Spryker\Shared\Log\Sanitizer\SanitizerInterface
     */
    protected $sanitizer;

    /**
     * @param \Spryker\Shared\Log\Sanitizer\SanitizerInterface $sanitizer
     */
    public function __construct(SanitizerInterface $sanitizer)
    {
        $this->sanitizer = $sanitizer;
    }

    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        if (isset($record[static::RECORD_CONTEXT][static::EXTRA])) {
            $body = $record[static::RECORD_CONTEXT][static::EXTRA];
            $record[static::RECORD_EXTRA][static::EXTRA] = $this->prepareBody($body);

            unset($record[static::RECORD_CONTEXT][static::EXTRA]);
        }

        return $record;
    }

    /**
     * @param string|array $body
     *
     * @return array
     */
    protected function prepareBody($body)
    {
        if ($this->isJson($body)) {
            $jsonUtil = new Json();
            $body = $jsonUtil->decode($body, true);
        }

        if (is_array($body)) {
            $body = $this->sanitizer->sanitize($body);
        }

        if (is_string($body)) {
            $body = ['transfer-response' => $body];
        }

        $body = $this->prepareValues($body);

        return $body;
    }

    /**
     * @param array $body
     *
     * @return array
     */
    protected function prepareValues(array $body)
    {
        foreach ($body as $key => $value) {
            if (is_array($value)) {
                $body[$key] = $this->prepareValues($value);
            }
            if (is_bool($value)) {
                $body[$key] = (int)$value;
            }
        }

        return $body;
    }

    /**
     * @param string $data
     *
     * @return bool
     */
    protected function isJson($data)
    {
        json_decode($data);

        return (json_last_error() === JSON_ERROR_NONE);
    }
}
