<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ChecksumGenerator;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\ChecksumGenerator\ChecksumGeneratorServiceFactory getFactory()
 */
class ChecksumGeneratorService extends AbstractService implements ChecksumGeneratorServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $data
     * @param string $encryptionKey
     * @param string $hexInitializationVector
     *
     * @return string
     */
    public function generateOpenSslChecksum(array $data, string $encryptionKey, string $hexInitializationVector): string
    {
        return $this->getFactory()
            ->createOpenSslChecksumGenerator()
            ->generateOpenSslChecksum($data, $encryptionKey, $hexInitializationVector);
    }
}
