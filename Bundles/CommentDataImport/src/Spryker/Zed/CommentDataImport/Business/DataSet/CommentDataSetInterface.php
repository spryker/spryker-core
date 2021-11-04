<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CommentDataImport\Business\DataSet;

interface CommentDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_MESSAGE_KEY = 'message_key';

    /**
     * @var string
     */
    public const COLUMN_OWNER_TYPE = 'owner_type';

    /**
     * @var string
     */
    public const COLUMN_OWNER_KEY = 'owner_key';

    /**
     * @var string
     */
    public const COLUMN_CUSTOMER_REFERENCE = 'customer_reference';

    /**
     * @var string
     */
    public const COLUMN_MESSAGE = 'message';

    /**
     * @var string
     */
    public const COLUMN_TAGS = 'tags';

    /**
     * @var string
     */
    public const ID_CUSTOMER = 'id_customer';

    /**
     * @var string
     */
    public const COMMENT_THREAD_OWNER_ID = 'comment_thread_owner_id';
}
