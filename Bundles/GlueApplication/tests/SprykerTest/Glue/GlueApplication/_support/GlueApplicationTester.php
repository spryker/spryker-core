<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication;

use Codeception\Actor;
use Codeception\Configuration;
use Generated\Shared\Transfer\ApiControllerConfigurationTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Symfony\Component\Finder\Finder;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerTest\Glue\GlueApplication\PHPMD)
 */
class GlueApplicationTester extends Actor
{
    use _generated\GlueApplicationTesterActions;

    /**
     * @var string
     */
    public const FAKE_APPLICATION = 'FAKE_APPLICATION';

    /**
     * @var string
     */
    public const FAKE_CONTROLLER = 'FAKE_CONTROLLER';

    /**
     * @var string
     */
    public const FAKE_METHOD = 'FAKE_METHOD';

    /**
     * @var string
     */
    public const FAKE_PATH = 'FAKE_PATH';

    /**
     * @var string
     */
    public const FAKE_PARAMETER_FOO = 'FAKE_PARAMETER_FOO';

    /**
     * @var string
     */
    public const FAKE_PARAMETER_BAR = 'FAKE_PARAMETER_BAR';

    /**
     * @var string
     */
    protected const RESPONSE_STATUS = '200';

    /**
     * @var string
     */
    protected const RESPONSE_CONTENT = 'test';

    /**
     * @var string
     */
    protected const CONTENT_TYPE = 'application/json';

    /**
     * @var string
     */
    protected const META_KEY = 'content-type';

    /**
     * @var string
     */
    protected const PATH = '/foo/foo-id';

    /**
     * @var string
     */
    protected const ALLOWED_HEADER = 'allowed-header';

    /**
     * @var string
     */
    protected const RESOURCE_TYPE = 'foo';

    /**
     * @return array<string, array<string, \Generated\Shared\Transfer\ApiControllerConfigurationTransfer>>
     */
    public function haveApiControllerConfigurationTransfers(): array
    {
        return [
            static::FAKE_APPLICATION => [
                sprintf('%s:%s:%s', static::FAKE_CONTROLLER, static::FAKE_PATH, static::FAKE_METHOD) =>
                    (new ApiControllerConfigurationTransfer())
                        ->setApiApplication(static::FAKE_APPLICATION)
                        ->setController(static::FAKE_CONTROLLER)
                        ->setMethod(static::FAKE_METHOD)
                        ->setPath(static::FAKE_PATH)
                        ->setParameters([static::FAKE_PARAMETER_FOO, static::FAKE_PARAMETER_BAR]),
            ],
        ];
    }

    /**
     * @return void
     */
    public function removeCacheFile(): void
    {
        if (file_exists(Configuration::dataDir() . DIRECTORY_SEPARATOR . GlueApplicationConfig::API_CONTROLLER_CACHE_FILENAME)) {
            $finder = new Finder();
            $finder->in(Configuration::dataDir())->name(GlueApplicationConfig::API_CONTROLLER_CACHE_FILENAME);
            if ($finder->count() > 0) {
                foreach ($finder as $fileInfo) {
                    unlink($fileInfo->getPathname());
                }
            }
        }
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function createGlueRequestTransfer(): GlueRequestTransfer
    {
        $glueRequestTransfer = (new GlueRequestTransfer())->setQueryFields([
            'include' => 'resource1,resource2',
            'fields' => [
                'items' => 'att1,att2,att3',
            ],
            'page' => [
                'limit' => 1,
                'offset' => 10,
            ],
            'sort' => 'field1,field2',
            'filter' => [
                'items.name' => 'item name',
            ],
        ])
            ->setPath(static::PATH)
            ->setMeta([static::META_KEY => [static::CONTENT_TYPE]])
            ->setResource($this->createGlueResourceTransfer());

        return $glueRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createGlueResponseTransfer(): GlueResponseTransfer
    {
        $glueResponseTransfer = (new GlueResponseTransfer())
            ->addResource($this->createGlueResourceTransfer())
            ->setStatus(static::RESPONSE_STATUS)
            ->setContent(static::RESPONSE_CONTENT);

        return $glueResponseTransfer;
    }

    /**
     * @return array<mixed>
     */
    public function createFormattedData(): array
    {
        return json_decode(trim($this->loadJsonFile()), true);
    }

    /**
     * @return array
     */
    public function createOperation(): array
    {
        return [
            'operationId' => 'get-collection-of-tests',
            'summary' => 'Retrieves collection of tests.',
            'parameters' => [
                [
                    '$ref' => '#/components/parameters/acceptLanguage',
                ],
                [
                    'name' => 'q',
                    'in' => 'query',
                    'description' => 'Description.',
                    'required' => true,
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'responses' => [
                [
                    'description' => 'Expected response to a valid request.',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/TestsRestResponse',
                            ],
                        ],
                        'application/vnd.api+json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/TestsCollectionRestResponse',
                            ],
                        ],
                    ],
                ],
                'default' => [
                    'description' => 'Expected response to a bad request.',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/TestsRestResponse',
                            ],
                        ],
                        'application/vnd.api+json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/JsonApiErrorMessage',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createGlueResourceTransfer(): GlueResourceTransfer
    {
        return (new GlueResourceTransfer())
            ->setType('articles')
            ->setId('1');
    }

    /**
     * @return string
     */
    protected function loadJsonFile(): string
    {
        return file_get_contents(codecept_data_dir() . 'schema.json.example');
    }
}
