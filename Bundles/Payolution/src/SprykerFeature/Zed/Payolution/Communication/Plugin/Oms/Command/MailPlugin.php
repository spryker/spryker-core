<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Communication\Plugin\Oms\Command;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use Spryker\Zed\Payolution\Business\PayolutionFacade;
use Spryker\Zed\Payolution\Communication\PayolutionDependencyContainer;
use Spryker\Zed\Payolution\PayolutionConfig;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\MailRecipientTransfer;

/**
 * @method PayolutionFacade getFacade()
 * @method PayolutionDependencyContainer getDependencyContainer()
 */
class MailPlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $config = $this->getDependencyContainer()->getConfig();
        $mailTransfer = new MailTransfer();

        $mailTransfer->setTemplateName($config->getEmailTemplateName());
        $this->addMailRecipient($mailTransfer, $orderEntity->getEmail());
        $this->setMailTransferFrom($mailTransfer, $config);
        $this->setMailTransferSubject($mailTransfer, $config);

        $mailFacade = $this->getDependencyContainer()->getMailFacade();
        $mailFacade->sendMail($mailTransfer);

        return [];
    }

    /**
     * @param MailTransfer $mailTransfer
     * @param string $email
     *
     * @return void
     */
    protected function addMailRecipient(MailTransfer $mailTransfer, $email)
    {
        $mailRecipientTransfer = new MailRecipientTransfer();
        $mailRecipientTransfer->setEmail($email);
        $mailTransfer->addRecipient($mailRecipientTransfer);
    }

    /**
     * @param MailTransfer $mailTransfer
     * @param PayolutionConfig $config
     *
     * @return void
     */
    protected function setMailTransferFrom(MailTransfer $mailTransfer, PayolutionConfig $config)
    {
        $fromName = $config->getEmailFromName();
        if ($fromName !== null) {
            $mailTransfer->setFromName($fromName);
        }

        $fromEmail = $config->getEmailFromAddress();
        if ($fromEmail !== null) {
            $mailTransfer->setFromEmail($fromEmail);
        }
    }

    /**
     * @param MailTransfer $mailTransfer
     * @param PayolutionConfig $config
     *
     * @return void
     */
    protected function setMailTransferSubject(MailTransfer $mailTransfer, PayolutionConfig $config)
    {
        $subject = $config->getEmailSubject();
        if ($subject !== null) {
            $mailTransfer->setSubject($this->translate($subject));
        }
    }

    /**
     * @param string $keyName
     *
     * @return string
     */
    protected function translate($keyName)
    {
        $glossaryFacade = $this->getDependencyContainer()->getGlossaryFacade();

        if ($glossaryFacade->hasTranslation($keyName)) {
            return $glossaryFacade->translate($keyName);
        }

        return $keyName;
    }

}
