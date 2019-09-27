<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlossaryStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\GlossaryStorage\GlossaryStorageFactory getFactory()
 */
class GlossaryStorageClient extends AbstractClient implements GlossaryStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $id
     * @param string $localeName
     * @param array $parameters
     *
     * @return string
     */
    public function translate($id, $localeName, array $parameters = [])
    {
        return $this
            ->getFactory()
            ->createTranslator()
            ->translate($id, $localeName, $parameters);
    }
}
