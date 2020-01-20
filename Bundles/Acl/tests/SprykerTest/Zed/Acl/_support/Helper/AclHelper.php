<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\GroupBuilder;
use Generated\Shared\Transfer\GroupTransfer;
use Orm\Zed\Acl\Persistence\SpyAclGroup;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class AclHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function haveGroup(array $seedData = []): GroupTransfer
    {
        $groupTransfer = (new GroupBuilder($seedData))->build();
        $aclGroupEntity = $this->createAclGroupEntity();
        $aclGroupEntity->fromArray($groupTransfer->toArray());

        $aclGroupEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($aclGroupEntity) {
            $aclGroupEntity->delete();
        });

        $groupTransfer->fromArray($aclGroupEntity->toArray(), true);

        return $groupTransfer;
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroup
     */
    private function createAclGroupEntity(): SpyAclGroup
    {
        return new SpyAclGroup();
    }
}
