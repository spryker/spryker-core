<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention;

use Codeception\Actor;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

/**
 * Inherited Methods
 *
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
class GlueJsonApiConventionTester extends Actor
{
    use _generated\GlueJsonApiConventionTesterActions;

    /**
     * @var string
     */
    public const COMPONENTS_PARAMETERS_CONTENT_TYPE = '#/components/parameters/ContentType';

    /**
     * @var string
     */
    public const COMPONENTS_PARAMETERS_PAGE = '#/components/parameters/Page';

    /**
     * @var string
     */
    public const COMPONENTS_PARAMETERS_FIELDS = '#/components/parameters/Fields';

    /**
     * @var string
     */
    public const COMPONENTS_PARAMETERS_FILTER = '#/components/parameters/Filter';

    /**
     * @var string
     */
    public const COMPONENTS_PARAMETERS_SORT = '#/components/parameters/Sort';

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
    protected const META_KEY = 'content-type';

    /**
     * @var string
     */
    protected const CONTENT_TYPE = 'application/vnd.api+json';

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
            'filter' => [
                'items.name' => 'item name',
            ],
            'sort' => 'field1,-field2',
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
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createGlueResourceTransfer(): GlueResourceTransfer
    {
        return (new GlueResourceTransfer())
            ->setType('articles')
            ->setId('1');
    }

    /**
     * @return array
     */
    public function createSchemaForamtedData(): array
    {
        return json_decode(trim($this->loadJosnFile()), true);
    }

    /**
     * @return string
     */
    protected function loadJosnFile(): string
    {
        return file_get_contents(codecept_data_dir() . 'schema.json.example');
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
                                '$ref' => '#/components/schemas/TestsRestCollectionResponse',
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
}
