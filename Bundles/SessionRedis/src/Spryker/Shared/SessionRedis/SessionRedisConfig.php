<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SessionRedisConfig extends AbstractSharedConfig
{
    public const SESSION_HANDLER_REDIS_NAME = 'redis';
    public const SESSION_HANDLER_REDIS_LOCKING_NAME = 'redis_locking';

    public const PROTOCOL_TCP = 'tcp';

    public const DATA_SOURCE_NAME_TEMPLATE_TCP = 'tcp://[host]:[port]?database=[database][authFragment]';
    public const AUTH_FRAGMENT_TEMPLATE_TCP = '&password=%s';

    public const DATA_SOURCE_NAME_TEMPLATE_REDIS = 'redis://[authFragment][host]:[port]/[database]';
    public const AUTH_FRAGMENT_TEMPLATE_REDIS = ':%s@';

    public const DEFAULT_REDIS_DATABASE = 0;

    /**
     * @param string $protocol
     * @param string $host
     * @param int $port
     * @param int $database
     * @param string $password
     *
     * @return string
     */
    public function buildDataSourceName($protocol, $host, $port, $database, $password): string
    {
        $authFragmentTemplate = $this->getAuthFragmentTemplate($protocol);
        $dataSourceNameTemplate = $this->getDataSourceNameTemplate($protocol);
        $authFragment = '';
        if ($password) {
            $authFragment = sprintf($authFragmentTemplate, $password);
        }

        $dataSourceNameElements = [
            '[host]' => $host,
            '[port]' => $port,
            '[database]' => $database,
            '[authFragment]' => $authFragment,
        ];

        return str_replace(
            array_keys($dataSourceNameElements),
            array_values($dataSourceNameElements),
            $dataSourceNameTemplate
        );
    }

    /**
     * @param string $protocol
     *
     * @return string
     */
    protected function getAuthFragmentTemplate($protocol): string
    {
        return ($protocol === $this->getProtocolTcp())
            ? $this->getAuthFragmentTemplateTcp()
            : $this->getAuthFragmentTemplateRedis();
    }

    /**
     * @param string $protocol
     *
     * @return string
     */
    protected function getDataSourceNameTemplate($protocol): string
    {
        return ($protocol === $this->getProtocolTcp())
            ? $this->getDataSourceNameTemplateTcp()
            : $this->getDataSourceNameTemplateRedis();
    }

    /**
     * @return string
     */
    public function getSessionHandlerRedisName(): string
    {
        return static::SESSION_HANDLER_REDIS_NAME;
    }

    /**
     * @return string
     */
    public function getSessionHandlerRedisLockingNae(): string
    {
        return static::SESSION_HANDLER_REDIS_LOCKING_NAME;
    }

    /**
     * @return string
     */
    public function getProtocolTcp(): string
    {
        return static::PROTOCOL_TCP;
    }

    /**
     * @return string
     */
    public function getDataSourceNameTemplateTcp(): string
    {
        return static::DATA_SOURCE_NAME_TEMPLATE_TCP;
    }

    /**
     * @return string
     */
    public function getAuthFragmentTemplateTcp(): string
    {
        return static::AUTH_FRAGMENT_TEMPLATE_TCP;
    }

    /**
     * @return string
     */
    public function getDataSourceNameTemplateRedis(): string
    {
        return static::DATA_SOURCE_NAME_TEMPLATE_REDIS;
    }

    /**
     * @return string
     */
    public function getAuthFragmentTemplateRedis(): string
    {
        return static::AUTH_FRAGMENT_TEMPLATE_REDIS;
    }

    /**
     * @return string
     */
    public function getDefaultRedisDatabase(): string
    {
        return static::DEFAULT_REDIS_DATABASE;
    }
}
