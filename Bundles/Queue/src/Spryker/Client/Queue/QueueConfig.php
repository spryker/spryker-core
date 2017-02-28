<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\Queue\QueueConstants;

class QueueConfig extends AbstractBundleConfig
{

    /**
     * @return array
     */
    public function getQueueAdapterNameMapping()
    {
        return $this->get(QueueConstants::QUEUE_ADAPTOR_NAME_MAPPING);
    }

    /**
     * @return string
     */
    public function getDefaultQueueAdapterName()
    {
        return $this->get(QueueConstants::QUEUE_ADAPTOR_NAME_DEFAULT);
    }
}
