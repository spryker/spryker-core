<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Glue\ServicePointsBackendApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\GlueBackendApiApplication\GlueBackendApiApplicationConstants;
use Spryker\Shared\ZedRequest\ZedRequestConstants;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class ServicePointsBackendApiTester extends Actor
{
    use _generated\ServicePointsBackendApiTesterActions;

    /**
     * @uses \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS
     *
     * @var string
     */
    protected const RESOURCE_SERVICE_POINTS = 'service-points';

    /**
     * @param array<string, mixed> $servicePointSeedData
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function createServicePointTransfer(array $servicePointSeedData = []): ServicePointTransfer
    {
        $storeTransfer = $this->haveStore();

        $servicePointTransfer = (new ServicePointBuilder($servicePointSeedData))
            ->withStoreRelation([
                StoreRelationTransfer::STORES => [
                    [
                        StoreTransfer::ID_STORE => $storeTransfer->getIdStore(),
                        StoreTransfer::NAME => $storeTransfer->getName(),
                    ],
                ],
            ])->build();

        return $this->haveServicePoint($servicePointTransfer->toArray());
    }

    /**
     * @param string|null $uuid
     *
     * @return string
     */
    public function buildServicePointUrl(?string $uuid = null): string
    {
        if ($uuid) {
            return $this->buildBackendApiUrl(
                sprintf('%s/%s', static::RESOURCE_SERVICE_POINTS, $uuid),
            );
        }

        return $this->buildBackendApiUrl(static::RESOURCE_SERVICE_POINTS);
    }

    /**
     * @param string $uri
     *
     * @return string
     */
    protected function buildBackendApiUrl(string $uri): string
    {
        $url = sprintf(
            '%s://%s/%s',
            Config::get(ZedRequestConstants::ZED_API_SSL_ENABLED) ? 'https' : 'http',
            Config::get(GlueBackendApiApplicationConstants::GLUE_BACKEND_API_HOST),
            $uri,
        );

        return rtrim($url, '/');
    }
}
