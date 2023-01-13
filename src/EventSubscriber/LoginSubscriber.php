<?php

namespace App\EventSubscriber;

// use App\Controller\Admin\HistoriqueConnexionController;

use App\Entity\HistoriqueConnexion;
use App\Repository\HistoriqueConnexionRepository;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    private HistoriqueConnexionRepository $historiqueConnexionRepository;
    private RequestStack $request;

    public function __construct(RequestStack $requestStack, HistoriqueConnexionRepository $historiqueConnexionRepo)
    {
        $this->historiqueConnexionRepository = $historiqueConnexionRepo;
        $this->request = $requestStack;
    }

    public function onSecurityAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        
        /**
         * @var User
         */
        
        $user = $event->getAuthenticationToken()->getUser();
        
        $url = $this->request->getCurrentRequest()->server->get("PATH_INFO");
        
        $adresseIp = $this->request->getCurrentRequest()->getClientIp();

        $historiqueConnexion = new HistoriqueConnexion();
        $historiqueConnexion->setDateConnexion(new DateTime())
            ->setEmail($user->getEmail())
            ->setNom($user->getNom())
            ->setPrenom($user->getPrenom())
            ->setAdresseIp($adresseIp)
            ->setUrl($url);
        $historiqueConnexion->setDerniereConnexion(new DateTime());  
        
        $this->historiqueConnexionRepository->add($historiqueConnexion, true);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'security.authentication.success' => 'onSecurityAuthenticationSuccess',
        ];
    }
}
