<?php
namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    private $twig;

    public function __constuct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }
    // filter la reponse 
    public  function methodCalledOnKernelResponse(FilterResponseEvent $filterResponseEvent)
    {
         $maintenance = false; // au cas ou si on est en maintenance

        if($maintenance) {
            //je cree une vue
            $content = $this->twig->render('maintenance/maintenance.html.twig');
            // je construit un objet response
            $response = new Response($content);
            //je linjecte dans la reponse qui sera rendu, il remplacera la reponse qui etait prevu
          return   $filterResponseEvent->setResponse($response);
        }

        return $filterResponseEvent->getResponse()->getContent();
    }

    public static function getSubscribedEvents()
    {
        // remplacer la reponse qui sera renvoyer par symfony si on est en maintenance
        return [
            KernelEvents::RESPONSE => ['methodCalledOnKernelResponse', 255]
        ];
    }

}