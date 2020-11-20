<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SearchElasticsearch;

use Codeception\Actor;
use Spryker\Shared\SearchElasticsearch\Logger\ElasticsearchInMemoryLogger;
use Spryker\Shared\SearchElasticsearch\Logger\ElasticsearchLoggerInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class SearchElasticsearchSharedTester extends Actor
{
    use _generated\SearchElasticsearchSharedTesterActions;

    public const DEFAULT_ELASTICSEARCH_PROTOCOL = 'http';
    public const DEFAULT_ELASTICSEARCH_HOST = 'localhost';
    public const DEFAULT_ELASTICSEARCH_PORT = '9001';

    /**
     * @param array $clientConfig
     *
     * @return \Spryker\Shared\SearchElasticsearch\Logger\ElasticsearchLoggerInterface
     */
    public function createElasticsearchInMemoryLogger(array $clientConfig = []): ElasticsearchLoggerInterface
    {
        $clientConfig = array_merge([
            'transport' => static::DEFAULT_ELASTICSEARCH_PROTOCOL,
            'host' => static::DEFAULT_ELASTICSEARCH_HOST,
            'port' => static::DEFAULT_ELASTICSEARCH_PORT,
        ], $clientConfig);

        return new ElasticsearchInMemoryLogger($clientConfig);
    }
}
