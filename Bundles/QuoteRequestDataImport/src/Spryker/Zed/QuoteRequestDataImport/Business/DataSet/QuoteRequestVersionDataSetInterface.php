<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\QuoteRequestDataImport\Business\DataSet;

interface QuoteRequestVersionDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_QUOTE_REQUEST_REFERENCE = 'quote_request_reference';

    /**
     * @var string
     */
    public const COLUMN_VERSION_REFERENCE = 'version_reference';

    /**
     * @var string
     */
    public const COLUMN_VERSION = 'version';

    /**
     * @var string
     */
    public const COLUMN_METADATA = 'metadata';

    /**
     * @var string
     */
    public const COLUMN_QUOTE = 'quote';

    /**
     * @var string
     */
    public const ID_QUOTE_REQUEST = 'id_quote_request';
}
