<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\Validator\Constraints;

use Spryker\Shared\Validator\Constraints\File as SprykerFile;

class Files extends SprykerFile
{
    /**
     * @var string|int|null
     */
    public $totalMaxSize = 0;

    public function validatedBy(): string
    {
        return FilesValidator::class;
    }
}
