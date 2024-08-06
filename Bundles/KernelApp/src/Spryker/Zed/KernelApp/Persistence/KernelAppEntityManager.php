<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\KernelApp\Persistence;

use Generated\Shared\Transfer\AppConfigTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\KernelApp\Persistence\KernelAppPersistenceFactory getFactory()
 */
class KernelAppEntityManager extends AbstractEntityManager implements KernelAppEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return void
     */
    public function writeAppConfig(AppConfigTransfer $appConfigTransfer): void
    {
        $appConfigTransfer->getAppIdentifierOrFail();
        $appConfigTransfer->getStatusOrFail();

        $appConfigData = $appConfigTransfer->modifiedToArray();
        $appConfigData[AppConfigTransfer::CONFIG] = $this->getFactory()->getUtilEncodingService()->encodeJson($appConfigData[AppConfigTransfer::CONFIG]);

        $appConfigPropelQuery = $this->getFactory()->createAppConfigPropelQuery();
        $appConfigEntity = $appConfigPropelQuery->filterByAppIdentifier($appConfigTransfer->getAppIdentifier())->findOneOrCreate();
        $appConfigEntity->fromArray($appConfigData);
        $appConfigEntity->save();
    }
}
