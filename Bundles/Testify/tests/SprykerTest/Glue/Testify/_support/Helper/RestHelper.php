<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\Helper;

use Codeception\Module\REST;

class RestHelper extends REST
{
    /**
     * @part json
     *
     * @param string $url
     * @param \JsonSerializable|array<mixed>|string $params
     * @param array<mixed> $files
     *
     * @return void
     */
    public function sendPost(string $url, $params = [], array $files = []): void
    {
        trigger_error(
            'You need to implement a custom method for specific convention. Please, follow the name patter "send<convention_name>Post"',
        );
    }

    /**
     * @part json
     *
     * @param string $url
     * @param \JsonSerializable|array<mixed>|string $params
     *
     * @return void
     */
    public function sendHead(string $url, array $params = []): void
    {
        trigger_error(
            'You need to implement a custom method for specific convention. Please, follow the name patter "send<convention_name>Head"',
        );
    }

    /**
     * @part json
     *
     * @param string $url
     * @param \JsonSerializable|array<mixed>|string $params
     *
     * @return void
     */
    public function sendOptions(string $url, array $params = []): void
    {
        trigger_error(
            'You need to implement a custom method for specific convention. Please, follow the name patter "send<convention_name>Options"',
        );
    }

    /**
     * @part json
     *
     * @param string $url
     * @param \JsonSerializable|array<mixed>|string $params
     *
     * @return void
     */
    public function sendGet(string $url, array $params = []): void
    {
        trigger_error(
            'You need to implement a custom method for specific convention. Please, follow the name patter "send<convention_name>Get"',
        );
    }

    /**
     * @part json
     *
     * @param string $url
     * @param \JsonSerializable|array<mixed>|string $params
     * @param array<mixed> $files
     *
     * @return void
     */
    public function sendPut(string $url, $params = [], array $files = []): void
    {
        trigger_error(
            'You need to implement a custom method for specific convention. Please, follow the name patter "send<convention_name>Put"',
        );
    }

    /**
     * @part json
     *
     * @param string $url
     * @param \JsonSerializable|array<mixed>|string $params
     * @param array<mixed> $files
     *
     * @return void
     */
    public function sendPatch(string $url, $params = [], array $files = []): void
    {
        trigger_error(
            'You need to implement a custom method for specific convention. Please, follow the name patter "send<convention_name>Patch"',
        );
    }

    /**
     * @part json
     *
     * @param string $url
     * @param \JsonSerializable|array<mixed>|string $params
     * @param array<mixed> $files
     *
     * @return void
     */
    public function sendDelete(string $url, array $params = [], array $files = []): void
    {
        trigger_error(
            'You need to implement a custom method for specific convention. Please, follow the name patter "send<convention_name>Delete"',
        );
    }

    /**
     * @part json
     *
     * @param string $method
     * @param string $url
     * @param \JsonSerializable|array<mixed>|string $params
     * @param array<mixed> $files
     *
     * @return void
     */
    public function send(string $method, string $url, $params = [], array $files = []): void
    {
        trigger_error(
            'You need to implement a custom method for specific convention. Please, follow the name patter "send<convention_name>"',
        );
    }

    /**
     * @part json
     *
     * @param string $url
     * @param array<mixed> $linkEntries
     *
     * @return void
     */
    public function sendLink(string $url, array $linkEntries): void
    {
        trigger_error(
            'You need to implement a custom method for specific convention. Please, follow the name patter "send<convention_name>Link"',
        );
    }

    /**
     * @part json
     *
     * @param string $url
     * @param array<mixed> $linkEntries
     *
     * @return void
     */
    public function sendUnlink(string $url, array $linkEntries): void
    {
        trigger_error(
            'You need to implement a custom method for specific convention. Please, follow the name patter "send<convention_name>Unlink"',
        );
    }
}
