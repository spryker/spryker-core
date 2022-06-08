<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerExtension\Dependency\Plugin;

interface MessageHandlerPluginInterface
{
    /**
     * Specification:
     * - Return an array where the key is the class name to be handled and the value is the callable that handles the message.
     *
     * @api
     *
     * @example
     * return [Foo::class => [$this, 'onFoo']]
     *
     * @return array<string, callable>
     */
    public function handles(): iterable;
}
