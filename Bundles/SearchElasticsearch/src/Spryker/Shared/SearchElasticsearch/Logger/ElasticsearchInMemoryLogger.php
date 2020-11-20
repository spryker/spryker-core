<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearch\Logger;

class ElasticsearchInMemoryLogger implements ElasticsearchLoggerInterface
{
    protected const URI_STRING_TEMPLATE_UNKNOWN = 'unknown';

    /**
     * @var array
     */
    protected static $logs = [];

    /**
     * @var array
     */
    protected $elasticsearchClientConfig;

    /**
     * @param array $elasticsearchClientConfig
     */
    public function __construct(array $elasticsearchClientConfig = [])
    {
        $this->elasticsearchClientConfig = $elasticsearchClientConfig;
    }

    /**
     * @param array $payload
     * @param mixed|null $result
     *
     * @return void
     */
    public function log(array $payload, $result = null): void
    {
        static::$logs[] = [
            'destination' => $this->buildElasticsearchUri(),
            'payload' => json_encode($payload, JSON_PRETTY_PRINT),
            'result' => json_encode($result, JSON_PRETTY_PRINT),
        ];
    }

    /**
     * @return array
     */
    public function getLogs(): array
    {
        return static::$logs;
    }

    /**
     * @return string
     */
    protected function buildElasticsearchUri(): string
    {
        return sprintf(
            '%s://%s:%d',
            $this->elasticsearchClientConfig['transport'] ?? static::URI_STRING_TEMPLATE_UNKNOWN,
            $this->elasticsearchClientConfig['host'] ?? static::URI_STRING_TEMPLATE_UNKNOWN,
            $this->elasticsearchClientConfig['port'] ?? static::URI_STRING_TEMPLATE_UNKNOWN
        );
    }
}
