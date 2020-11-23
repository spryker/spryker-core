<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearch\Logger;

use Spryker\Shared\SearchElasticsearch\Dependency\Service\SearchElasticsearchToUtilEncodingServiceInterface;

class ElasticsearchInMemoryLogger implements ElasticsearchLoggerInterface
{
    protected const URI_STRING_TEMPLATE_UNKNOWN = 'unknown';

    /**
     * @var array
     */
    protected static $logs = [];

    /**
     * @var \Spryker\Shared\SearchElasticsearch\Dependency\Service\SearchElasticsearchToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var array
     */
    protected $elasticsearchClientConfig;

    /**
     * @param \Spryker\Shared\SearchElasticsearch\Dependency\Service\SearchElasticsearchToUtilEncodingServiceInterface $utilEncodingService
     * @param array $elasticsearchClientConfig
     */
    public function __construct(
        SearchElasticsearchToUtilEncodingServiceInterface $utilEncodingService,
        array $elasticsearchClientConfig = []
    ) {
        $this->utilEncodingService = $utilEncodingService;
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
            'payload' => $this->utilEncodingService->encodeJson($payload, JSON_PRETTY_PRINT),
            'result' => $this->utilEncodingService->encodeJson($result, JSON_PRETTY_PRINT),
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
            '%s://%s:%s',
            $this->elasticsearchClientConfig['transport'] ?? static::URI_STRING_TEMPLATE_UNKNOWN,
            $this->elasticsearchClientConfig['host'] ?? static::URI_STRING_TEMPLATE_UNKNOWN,
            $this->elasticsearchClientConfig['port'] ?? static::URI_STRING_TEMPLATE_UNKNOWN
        );
    }
}
