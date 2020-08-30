<?php

/*
 * @copyright   2020 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticContactActionsBundle\EventListener;

use Mautic\CoreBundle\Entity\IpAddress;
use Mautic\CoreBundle\Helper\ArrayHelper;
use Mautic\LeadBundle\Event\LeadEvent;
use Mautic\LeadBundle\LeadEvents;
use Mautic\LeadBundle\Model\LeadModel;
use MauticPlugin\MauticContactActionsBundle\Entity\ContactActions;
use MauticPlugin\MauticContactActionsBundle\Entity\ContactActionsLog;
use MauticPlugin\MauticContactActionsBundle\Integration\ContactActionsSettings;
use MauticPlugin\MauticContactActionsBundle\Model\ContactActionsModel;
use MauticPlugin\MauticContactActionsBundle\Trigger\TriggerFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LeadSubscriber implements EventSubscriberInterface
{
    /**
     * @var ContactActionsSettings
     */
    private $contactActionsSettings;

    /**
     * @var LeadModel
     */
    private $leadModel;

    /**
     * LeadSubscriber constructor.
     *
     * @param ContactActionsSettings $contactActionsSettings
     * @param LeadModel              $leadModel
     */
    public function __construct(ContactActionsSettings $contactActionsSettings, LeadModel $leadModel)
    {
        $this->contactActionsSettings = $contactActionsSettings;
        $this->leadModel              = $leadModel;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            LeadEvents::LEAD_PRE_SAVE    => ['onLeadPreSave', 0],
        ];
    }

    public function onLeadPreSave(LeadEvent $event)
    {
        if ($countryIsoCodeField = $this->contactActionsSettings->getCountryIsoCodeField()) {
            $lead    = $event->getLead();
            $changes = $lead->getChanges(true);

            if ($lead->getFieldValue($countryIsoCodeField)) {
                return;
            }
            if (empty($changes['ipAddresses'])) {
                return;
            }

            if (empty($changes['ipAddressList'])) {
                return;
            }

            $changedIpAddress = ArrayHelper::getValue($changes['ipAddresses'][1], $changes['ipAddressList']);

            if ($changedIpAddress instanceof IpAddress) {
                if ($countryIsocCodeValue = ArrayHelper::getValue(
                    'countryIsoCode',
                    $changedIpAddress->getIpDetails()
                )) {
                    $this->leadModel->setFieldValues($lead, [$countryIsoCodeField => $countryIsocCodeValue]);
                }
            }
        }
    }
}
