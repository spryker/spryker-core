<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence\Repository;

use Propel\Runtime\ActiveQuery\Criteria;

interface RelationMapperInterface
{
    /**
     * With this method you can add additional relations to your transfer collection without doing query for each entity.
     *
     * For example:
     *  $blogCollection = $this->buildQueryFromCriteria($this->getFactory()->createBlogQuery())->find();
     *  This will return only blog posts without relations.
     *
     *  Each blog posts have comments, to add it
     *   $commentCollection = $this->populateCollectionWithRelation($blogCollection, 'SpyBlogComment');
     *   This will populate each entity with related comments. SpyBlogEntity will have array of SpyBlogCommentEntityTransfer[]
     *
     *  To add related data for each comments use
     *   $this->populateCollectionWithRelation($commentCollection, 'SpyBlogCustomer');
     *   Now each blog comment have customer associated.
     *
     * @param \Spryker\Shared\Kernel\Transfer\EntityTransferInterface[] $collection
     * @param string $relation
     * @param \Propel\Runtime\ActiveQuery\Criteria|null $criteria
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface[]
     */
    public function populateCollectionWithRelation(array &$collection, $relation, ?Criteria $criteria = null);
}
