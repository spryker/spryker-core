<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\FileManagerDataImport\Business\DataSet;

interface MimeTypeDataSetInterface
{
    /**
     * @var string
     */
    public const KEY_NAME = 'name';

    /**
     * @var string
     */
    public const KEY_IS_ALLOWED = 'is_allowed';

    /**
     * @var string
     */
    public const KEY_EXTENSIONS = 'extensions';
}
