<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Glue\DynamicEntityBackendApi;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Storage\StorageConstants;

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
 * @SuppressWarnings(\SprykerTest\Glue\DynamicEntityBackendApi\PHPMD)
 */
class DynamicEntityBackendApiTester extends Actor
{
    use _generated\DynamicEntityBackendApiTesterActions;

    /**
     * @var string
     */
    public const TABLE_NAME = 'spy_dynamic_entity_configuration';

    /**
     * @var string
     */
    public const BAR_TABLE_NAME = 'spy_bar';

    /**
     * @var string
     */
    public const FOO_TABLE_ALIAS = 'foo';

    /**
     * @var string
     */
    public const BAR_TABLE_ALIAS = 'bar';

    /**
     * @var string
     */
    public const TABLE_ALIAS_COLUMN = 'table_alias';

    /**
     * @var string
     */
    public const TABLE_NAME_COLUMN = 'table_name';

    /**
     * @var string
     */
    public const DEFINITION_COLUMN = 'definition';

    /**
     * @var string
     */
    public const DEFINITION_UPDATED_VALUE = 'Definition is updated.';

    /**
     * @var string
     */
    public const DEFINITION_CREATED_VALUE = 'Definition is created.';

    /**
     * @var string
     */
    public const KEY_DATA = 'data';

    /**
     * @var string
     */
    protected const X_REAL_IP_HEADER = 'x-real-ip';

    /**
     * @var string
     */
    protected const REQUESTED_FORMAT = 'application/json';

    /**
     * @var string
     */
    protected const FOO_IP = '0.0.0.0';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_SCHEME
     *
     * @var string
     */
    protected const REDIS_SCHEME = 'STORAGE_REDIS:STORAGE_REDIS_SCHEME';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_HOST
     *
     * @var string
     */
    protected const REDIS_HOST = 'STORAGE_REDIS:STORAGE_REDIS_HOST';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PORT
     *
     * @var string
     */
    protected const REDIS_PORT = 'STORAGE_REDIS:STORAGE_REDIS_PORT';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_DATABASE
     *
     * @var string
     */
    protected const REDIS_DATABASE = 'STORAGE_REDIS:STORAGE_REDIS_DATABASE';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PASSWORD
     *
     * @var string
     */
    protected const REDIS_PASSWORD = 'STORAGE_REDIS:STORAGE_REDIS_PASSWORD';

    /**
     * @param string|null $tableAlias
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function haveGlueRequestTransfer(?string $tableAlias = null): GlueRequestTransfer
    {
        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer->setPath($this->getPath($tableAlias));
        $glueRequestTransfer->setResource(new GlueResourceTransfer());
        $glueRequestTransfer->setRequestedFormat(static::REQUESTED_FORMAT);
        $glueRequestTransfer->setMeta([
            static::X_REAL_IP_HEADER => [static::FOO_IP],
        ]);

        return $glueRequestTransfer;
    }

    /**
     * @param bool $isCreatable
     * @param bool $isRequired
     * @param bool $isEditable
     *
     * @return string
     */
    public function buildDefinitionWithNonAutoIncrementedId(
        bool $isCreatable = true,
        bool $isRequired = false,
        bool $isEditable = true
    ): string {
        $definitions = [
            'identifier' => 'table_alias',
            'fields' => [
                [
                    'fieldName' => 'id_dynamic_entity_configuration',
                    'fieldVisibleName' => 'id_dynamic_entity_configuration',
                    'type' => 'integer',
                    'isEditable' => false,
                    'isCreatable' => false,
                    'validation' => [
                        'isRequired' => false,
                    ],
                ], [
                    'fieldName' => 'table_alias',
                    'fieldVisibleName' => 'table_alias',
                    'type' => 'string',
                    'isEditable' => $isEditable,
                    'isCreatable' => $isCreatable,
                    'validation' => [
                        'isRequired' => false,
                    ],
                ], [
                    'fieldName' => 'table_name',
                    'fieldVisibleName' => 'table_name',
                    'type' => 'string',
                    'isEditable' => true,
                    'isCreatable' => true,
                    'validation' => [
                        'isRequired' => $isRequired,
                    ],
                ], [
                    'fieldName' => 'definition',
                    'fieldVisibleName' => 'definition',
                    'type' => 'string',
                    'isEditable' => true,
                    'isCreatable' => true,
                    'validation' => [
                        'isRequired' => false,
                    ],
                ],
            ],
        ];

        return json_encode($definitions);
    }

    /**
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function createDynamicEntityConfigurationTransfer(): DynamicEntityConfigurationTransfer
    {
        $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())->setFieldName('test-field-name');
        $dynamicEntityFieldDefinitionTransfer->setFieldVisibleName('test')
            ->setType('string')
            ->setIsEditable(true)
            ->setIsCreatable(true)
            ->setValidation((new DynamicEntityFieldValidationTransfer())->setIsRequired(false));

        $dynamicEntityDefinitionTransfer = new DynamicEntityDefinitionTransfer();
        $dynamicEntityDefinitionTransfer->setIdentifier('test-identifier')
            ->setFieldDefinitions(new ArrayObject([
                $dynamicEntityFieldDefinitionTransfer,
            ]));

        $dynamicEntityConfigurationTransfer = new DynamicEntityConfigurationTransfer();
        $dynamicEntityConfigurationTransfer->setTableAlias('test-resource')
            ->setTableName('test-table')
            ->setDynamicEntityDefinition($dynamicEntityDefinitionTransfer);

        return $dynamicEntityConfigurationTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function createDynamicEntityConfigurationTransferWithEmtpyFieldDefinitions(): DynamicEntityConfigurationTransfer
    {
        $dynamicEntityDefinitionTransfer = new DynamicEntityDefinitionTransfer();
        $dynamicEntityDefinitionTransfer
            ->setIdentifier('test-identifier')
            ->setFieldDefinitions(new ArrayObject([]));

        $dynamicEntityConfigurationTransfer = new DynamicEntityConfigurationTransfer();
        $dynamicEntityConfigurationTransfer->setTableAlias('test-resource')
            ->setTableName('test-table')
            ->setDynamicEntityDefinition($dynamicEntityDefinitionTransfer);

        return $dynamicEntityConfigurationTransfer;
    }

    /**
     * @param string $fileName
     *
     * @return array<mixed>
     */
    public function getExpectedPathData(string $fileName): array
    {
        return require codecept_data_dir() . $fileName;
    }

    /**
     * @return void
     */
    public function setupStorageRedisConfig(): void
    {
        $this->setConfig(StorageConstants::STORAGE_REDIS_PROTOCOL, Config::get(static::REDIS_SCHEME, false));
        $this->setConfig(StorageConstants::STORAGE_REDIS_PORT, Config::get(static::REDIS_PORT));
        $this->setConfig(StorageConstants::STORAGE_REDIS_HOST, Config::get(static::REDIS_HOST));
        $this->setConfig(StorageConstants::STORAGE_REDIS_DATABASE, Config::get(static::REDIS_DATABASE));
        $this->setConfig(StorageConstants::STORAGE_REDIS_PASSWORD, Config::get(static::REDIS_PASSWORD));
    }

    /**
     * @param string|null $tableAlias
     *
     * @return string
     */
    protected function getPath(?string $tableAlias = null): string
    {
        return sprintf(
            '/%s/%s',
            (new DynamicEntityBackendApiConfig())->getRoutePrefix(),
            $tableAlias ?? static::FOO_TABLE_ALIAS,
        );
    }
}
