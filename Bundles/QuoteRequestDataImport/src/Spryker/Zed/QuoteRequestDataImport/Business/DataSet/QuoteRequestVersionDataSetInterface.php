<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\QuoteRequestDataImport\Business\DataSet;

interface QuoteRequestVersionDataSetInterface
{
    public const COLUMN_QUOTE_REQUEST_REFERENCE = 'quote_request_reference';
    public const COLUMN_VERSION_REFERENCE = 'version_reference';
    public const COLUMN_VERSION = 'version';
    public const COLUMN_METADATA = 'metadata';
    public const COLUMN_QUOTE = 'quote';

    public const ID_QUOTE_REQUEST = 'id_quote_request';
}
