<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SearchElasticsearch\WebProfiler\DataCollector;

use Spryker\Shared\SearchElasticsearch\Logger\ElasticsearchLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Throwable;

class ElasticsearchDataCollector extends DataCollector
{
    /**
     * @var \Spryker\Shared\SearchElasticsearch\Logger\ElasticsearchInMemoryLogger
     */
    protected $elasticsearchLogger;

    /**
     * @param \Spryker\Shared\SearchElasticsearch\Logger\ElasticsearchLoggerInterface $elasticsearchLogger
     */
    public function __construct(ElasticsearchLoggerInterface $elasticsearchLogger)
    {
        $this->elasticsearchLogger = $elasticsearchLogger;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \Throwable|null $exception
     *
     * @return void
     */
    public function collect(Request $request, Response $response, ?Throwable $exception = null)
    {
        $this->data['calls'] = $this->elasticsearchLogger->getLogs();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'elasticsearch';
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->data = [];
    }

    /**
     * @return array
     */
    public function getCalls(): array
    {
        return $this->data['calls'];
    }
}
