<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Content;

interface ContentTypeInterface
{
    /**
     * @return string
     */
    public function getCategoryCandidateKey(): string;

    /**
     * @return string
     */
    public function getCandidateKey(): string;
}
