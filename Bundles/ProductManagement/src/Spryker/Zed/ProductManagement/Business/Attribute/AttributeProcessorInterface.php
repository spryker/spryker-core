<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Spryker\Shared\Library\Collection\CollectionInterface;

interface AttributeProcessorInterface
{

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    public function getAbstractAttributes();

    /**
     * @param \Spryker\Shared\Library\Collection\CollectionInterface $abstractAttributes
     */
    public function setAbstractAttributes(CollectionInterface $abstractAttributes);

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    public function getAttributes();

    /**
     * @param \Spryker\Shared\Library\Collection\CollectionInterface $attributes
     */
    public function setAttributes(CollectionInterface $attributes);

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    public function getLocalizedAttributes();

    /**
     * @param \Spryker\Shared\Library\Collection\CollectionInterface $localizedAttributes
     */
    public function setLocalizedAttributes(CollectionInterface $localizedAttributes);

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    public function getAbstractLocalizedAttributes();

    /**
     * @param \Spryker\Shared\Library\Collection\CollectionInterface $abstractLocalizedAttributes
     */
    public function setAbstractLocalizedAttributes(CollectionInterface $abstractLocalizedAttributes);

    /**
     * @return array
     */
    public function mergeAttributes();

}
