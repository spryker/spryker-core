<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\Testify\Helper;

use Codeception\Exception\ModuleException;
use SprykerTest\Shared\Testify\Helper\ModuleLocatorTrait;

trait LastConnectionConsumerTrait
{
    use ModuleLocatorTrait;

    /**
     * @throws \Codeception\Exception\ModuleException
     *
     * @return \SprykerTest\Glue\Testify\Helper\JsonConnection
     */
    protected function getJsonLastConnection(): JsonConnection
    {
        /** @var \SprykerTest\Glue\Testify\Helper\LastConnectionProviderInterface $lastConnectionProvider */
        $lastConnectionProvider = $this->locateModule(LastConnectionProviderInterface::class);
        $connection = $lastConnectionProvider->getLastConnection();

        if ($connection === null) {
            throw new ModuleException(
                $this,
                'Connection have not be performed yet. Use `$I->sendXXX()` methods to send HTTP request'
            );
        }

        if ($connection instanceof JsonConnection) {
            return $connection;
        }

        throw new ModuleException($this, 'OpenApi3 require JSON connection');
    }
}
