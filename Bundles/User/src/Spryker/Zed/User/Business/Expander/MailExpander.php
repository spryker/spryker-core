<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business\Expander;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Spryker\Zed\User\Business\Model\UserInterface;

class MailExpander implements MailExpanderInterface
{
    /**
     * @var \Spryker\Zed\User\Business\Model\UserInterface
     */
    protected $userModel;

    /**
     * @param \Spryker\Zed\User\Business\Model\UserInterface $userModel
     */
    public function __construct(UserInterface $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function expandMailWithUserData(MailTransfer $mailTransfer): MailTransfer
    {
        $email = $this->getEmailFromMailTransfer($mailTransfer);
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setEmail($email);
        $userTransfer = $this->userModel->findUser($userCriteriaTransfer);

        return $mailTransfer->setUser($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return string
     */
    protected function getEmailFromMailTransfer(MailTransfer $mailTransfer): string
    {
        $mailRecipientTransfer = $mailTransfer->requireRecipients()->getRecipients()[0];
        $mailRecipientTransfer->requireEmail();

        return $mailRecipientTransfer->getEmail();
    }
}
