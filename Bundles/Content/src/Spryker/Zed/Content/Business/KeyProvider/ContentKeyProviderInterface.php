<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Business\KeyProvider;

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */
interface ContentKeyProviderInterface
{
    /**
     * @throws \Spryker\Zed\Content\Business\Exception\ContentKeyNotCreatedException
     *
     * @return string
     */
    public function generateContentKey(): string;

    /**
     * @param int $idContent
     *
     * @return string
     */
    public function getContentKeyByIdContent(int $idContent): string;
}
