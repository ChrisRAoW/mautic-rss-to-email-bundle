<?php
namespace MauticPlugin\MauticRssToEmailBundle\EventListener;

use MauticPlugin\MauticRssToEmailBundle\Parser\Parser;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailSendEvent;

/**
 * Class EmailSubscriber
 */
class EmailSubscriber extends CommonSubscriber
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            EmailEvents::EMAIL_ON_SEND    => array('onEmailGenerate', 300),
            EmailEvents::EMAIL_ON_DISPLAY => array('onEmailGenerate', 300),
        );
    }

    /**
     * Search and replace tokens with content
     *
     * @param EmailSendEvent $event
     */
    public function onEmailGenerate(EmailSendEvent $event)
    {
        // // Get content
        $content = $event->getContent();
        $parser  = new Parser($content, $event);
        $content = $parser->getContent();

        $event->setContent($content);

        // Also replace feed items in the subject
        $content = $event->getSubject();
        $parser  = new Parser($content, $event);
        $content = $parser->getContent();
        $event->setSubject($content);
    }
}
