<?php
namespace MauticPlugin\MauticRssToEmailBundle\EventListener;

use MauticPlugin\MauticRssToEmailBundle\Parser\Parser;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailSendEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class EmailSubscriber
 */
class EmailSubscriber implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EmailEvents::EMAIL_ON_SEND    => ['onEmailGenerate', 300],
            EmailEvents::EMAIL_ON_DISPLAY => ['onEmailGenerate', 300],
        ];
    }

    /**
     * Search and replace tokens with content
     *
     * @param EmailSendEvent $event
     */
    public function onEmailGenerate(EmailSendEvent $event)
    {
        // Get HTML Content, Parse it, Generate New Content
        $content = $event->getContent();
        $parser  = new Parser($content, $event);
        $content = $parser->getContent();

        // Set HTML Content
        $event->setContent($content);

        // Get Plain Text Content, Parse it, Generate New Content
        $content = $event->getPlainText();
        $parser  = new Parser($content, $event);
        $content = $parser->getContent();

        // Set Plain Text Content
        $event->setPlainText($content);

        // Also replace feed items in the subject
        $content = $event->getSubject();
        $parser  = new Parser($content, $event);
        $content = $parser->getContent();
        $event->setSubject($content);
    }
}
