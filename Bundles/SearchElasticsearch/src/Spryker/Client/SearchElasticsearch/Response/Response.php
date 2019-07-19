<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Response;

use Elastica\Response as ElasticaResponse;
use Spryker\Client\SearchExtension\Dependency\Response\ResponseInterface;

class Response implements ResponseInterface
{
    /**
     * @var \Elastica\Response
     */
    protected $response;

    /**
     * @param \Elastica\Response $response
     */
    public function __construct(ElasticaResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->response->getError();
    }

    /**
     * @return array|string|null
     */
    public function getFullError()
    {
        return $this->response->getFullError();
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->response->getErrorMessage();
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->response->hasError();
    }

    /**
     * @return bool
     */
    public function hasFailedShards(): bool
    {
        return $this->response->hasFailedShards();
    }

    /**
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->response->isOk();
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->response->getStatus();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->response->getData();
    }

    /**
     * @return array
     */
    public function getTransferInfo(): array
    {
        return $this->response->getTransferInfo();
    }

    /**
     * @return float
     */
    public function getQueryTime(): float
    {
        return $this->response->getQueryTime();
    }

    /**
     * @return int
     */
    public function getEngineTime(): int
    {
        return $this->response->getEngineTime();
    }

    /**
     * @return array
     */
    public function getShardsStatistics(): array
    {
        return $this->response->getShardsStatistics();
    }
}
