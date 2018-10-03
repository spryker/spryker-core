<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NewRelic\Business\Model;

use GuzzleHttp\Client;
use Spryker\Shared\Config\Config;
use Spryker\Shared\NewRelic\NewRelicConstants;
use Spryker\Zed\NewRelic\Business\Exception\RecordDeploymentException;

class RecordDeployment implements RecordDeploymentInterface
{
    public const NEWRELIC_DEPLOYMENT_API_URL = 'https://api.newrelic.com/deployments.xml';
    public const STATUS_CODE_SUCCESS = 200;
    public const STATUS_CODE_REDIRECTION = 300;

    /**
     * @param array $arguments
     *
     * @throws \Spryker\Zed\NewRelic\Business\Exception\RecordDeploymentException
     *
     * @return $this
     */
    public function recordDeployment(array $arguments = [])
    {
        $response = $this->createRecordDeploymentRequest($arguments);
        $statusCode = $response->getStatusCode();
        if ($statusCode < static::STATUS_CODE_SUCCESS || $statusCode >= static::STATUS_CODE_REDIRECTION) {
            throw new RecordDeploymentException(sprintf(
                'Record deployment to New Relic request failed with code %d. %s',
                $response->getStatusCode(),
                $response->getBody()
            ));
        }

        return $this;
    }

    /**
     * @param array $params
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function createRecordDeploymentRequest(array $params)
    {
        $options = [
            'headers' => [
                'x-api-key' => Config::get(NewRelicConstants::NEWRELIC_API_KEY),
            ],
        ];

        $data = [];
        $data['deployment'] = [];
        foreach ($params as $key => $value) {
            $data['deployment'][$key] = $value;
        }
        $options['form_params'] = $data;

        $httpClient = new Client();

        $request = $httpClient->post(static::NEWRELIC_DEPLOYMENT_API_URL, $options);

        return $request;
    }
}
