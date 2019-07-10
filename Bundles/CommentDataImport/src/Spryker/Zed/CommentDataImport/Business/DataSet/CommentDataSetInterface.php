<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CommentDataImport\Business\DataSet;

interface CommentDataSetInterface
{
    public const COLUMN_MESSAGE_KEY = 'message_key';
    public const COLUMN_OWNER_TYPE = 'owner_type';
    public const COLUMN_OWNER_KEY = 'owner_key';
    public const COLUMN_CUSTOMER_REFERENCE = 'customer_reference';
    public const COLUMN_MESSAGE = 'message';
    public const COLUMN_TAGS = 'tags';

    public const ID_CUSTOMER = 'id_customer';
    public const COMMENT_THREAD_OWNER_ID = 'comment_thread_owner_id';
}
