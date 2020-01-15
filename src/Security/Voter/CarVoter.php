<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CarVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        
        return in_array($attribute, ['EDIT', 'DELETE'])// si cest pas une methode edit ou delete ou type car il return false
            && $subject instanceof \App\Entity\Car;
    }

    protected function voteOnAttribute($attribute, $car, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        if($user->isAdmin()){
            return true;
        }
        if(null == $car->getUser()){ // si mon car a un utilisateur si la voiture que je veut checker na pas de droit je reutrun false
            return false;
        }
        switch ($attribute) {
            case 'EDIT':
                return $car->getUser()->getId() == $user->getId();
                break;
            case 'DELETE':
                return $car->getUser()->getId() == $user->getId();
                
                break;
        }

        return false;
    }
}
