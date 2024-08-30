<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\IncrementalInstaller;

use Codeception\Actor;
use Generated\Shared\Transfer\IncrementalInstallerTransfer;
use Orm\Zed\IncrementalInstaller\Persistence\SpyIncrementalInstallerQuery;

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
 * @SuppressWarnings(PHPMD)
 */
class IncrementalInstallerBusinessTester extends Actor
{
    use _generated\IncrementalInstallerBusinessTesterActions;

    /**
     * @param string $installerName
     *
     * @return \Generated\Shared\Transfer\IncrementalInstallerTransfer|null
     */
    public function getInstallerByName(string $installerName): ?IncrementalInstallerTransfer
    {
        $incrementalInstallerEntity = SpyIncrementalInstallerQuery::create()
            ->filterByInstaller($installerName)
            ->findOne();

        if ($incrementalInstallerEntity === null) {
            return null;
        }

        return (new IncrementalInstallerTransfer())
            ->fromArray($incrementalInstallerEntity->toArray(), true);
    }

    /**
     * @param string $installerName
     * @param int $batch
     *
     * @return \Generated\Shared\Transfer\IncrementalInstallerTransfer
     */
    public function haveIncrementalInstaller(string $installerName, int $batch): IncrementalInstallerTransfer
    {
        $incrementalInstallerEntity = SpyIncrementalInstallerQuery::create()
            ->filterByInstaller($installerName)
            ->findOneOrCreate();

        $incrementalInstallerEntity->setBatch($batch);
        $incrementalInstallerEntity->save();

        return (new IncrementalInstallerTransfer())
            ->fromArray($incrementalInstallerEntity->toArray(), true);
    }
}
