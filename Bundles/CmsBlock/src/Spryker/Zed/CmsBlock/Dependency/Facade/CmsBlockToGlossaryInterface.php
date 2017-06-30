<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Dependency\Facade;

use Generated\Shared\Transfer\KeyTranslationTransfer;

interface CmsBlockToGlossaryInterface
{

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteTranslationsByFkKeys(array $keys);

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteKeys(array $keys);

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName);

    /**
     * @param \Generated\Shared\Transfer\KeyTranslationTransfer $keyTranslationTransfer
     *
     * @return bool
     */
    public function saveGlossaryKeyTranslations(KeyTranslationTransfer $keyTranslationTransfer);

}
