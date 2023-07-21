<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Asset\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\AssetTransfer;

class AssetAssertionHelper extends Module
{
    use AssetDataHelperTrait;

    /**
     * @param string $assetUuid
     *
     * @return void
     */
    public function assertAssetWithUuidExists(string $assetUuid): void
    {
        $assetTransfer = $this->getAssetDataHelper()->getPersistedAssetByUuid($assetUuid);

        $this->assertNotNull($assetTransfer, sprintf('Expected Asset with identifier "%s" was not found.', $assetUuid));
    }

    /**
     * @param string $assetUuid
     *
     * @return void
     */
    public function assertAssetWithUuidDoesNotExists(string $assetUuid): void
    {
        $assetTransfer = $this->getAssetDataHelper()->getPersistedAssetByUuid($assetUuid);

        $this->assertNull($assetTransfer, sprintf('Expected that the Asset with identifier "%s" was deleted but it was found.', $assetUuid));
    }

    /**
     * @param string $assetUuid
     *
     * @return void
     */
    public function assertAssetWithUuidIsInactive(string $assetUuid): void
    {
        $assetTransfer = $this->getAssetDataHelper()->getPersistedAssetByUuid($assetUuid);

        $this->assertNotNull($assetTransfer, sprintf('Expected Asset with identifier "%s" was not found.', $assetUuid));
        $this->assertFalse($assetTransfer->getIsActive(), sprintf('Expected Asset with identifier "%s" is inactive but it is active.', $assetUuid));
    }

    /**
     * @param string $assetUuid
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return void
     */
    public function assertAssetWithUuidEquals(string $assetUuid, AssetTransfer $assetTransfer): void
    {
        $persistedAssetTransfer = $this->getAssetDataHelper()->getPersistedAssetByUuid($assetUuid);

        $this->assertEquals($assetTransfer->getAssetUuid(), $persistedAssetTransfer->getAssetUuid(), sprintf(
            'Expected that the persisted Asset with UUID "%s" equals the passed UUID "%s".',
            $assetTransfer->getAssetUuid(),
            $persistedAssetTransfer->getAssetUuid(),
        ));

        $this->assertEquals($assetTransfer->getAssetName(), $persistedAssetTransfer->getAssetName(), sprintf(
            'Expected that the persisted Asset with name "%s" equals the passed name "%s".',
            $assetTransfer->getAssetName(),
            $persistedAssetTransfer->getAssetName(),
        ));

        $this->assertEquals($assetTransfer->getAssetContent(), $persistedAssetTransfer->getAssetContent(), sprintf(
            'Expected that the persisted Asset with content "%s" equals the passed content "%s".',
            $assetTransfer->getAssetContent(),
            $persistedAssetTransfer->getAssetContent(),
        ));

        $this->assertEquals($assetTransfer->getAssetSlot(), $persistedAssetTransfer->getAssetSlot(), sprintf(
            'Expected that the persisted Asset with slot "%s" equals the passed slot "%s".',
            $assetTransfer->getAssetSlot(),
            $persistedAssetTransfer->getAssetSlot(),
        ));
    }
}
