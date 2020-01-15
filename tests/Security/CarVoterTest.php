<?php

namespace App\Tests\Security;

use App\Entity\Car;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Security\Voter\CarVoter;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class CarVoterTest extends TestCase
{
    /**
     *@dataProvider voterProvider
     */
    public function testCarVoter($user, $expected)
    {
        $voter = new CarVoter();

        $car = new Car();

        // $user = $this->createMock(User::class); si on met provider $user dans parametre on commente 
        // $user->method('getId')->willReturn(1);

        // je crée un token anonyme de base lorsquil est pas connecte
        $token = new AnonymousToken('secret', 'anonymous');

        if($user){// si j'ai lutilisateur il me met dans la session et dans lobjet car
            $token = new UsernamePasswordToken($user, 'credentials', 'memory');
            $car->setUser($user);
        }
        

        $this->assertSame(1, $voter->vote($token, $car, ['EDIT']));
    }

    // public function testCarVoterCannotEdit()
    // {
    //     $voter = new CarVoter();

    //     $car = new Car();

    //     // $user = $this->createMock(User::class);
    //     // $user->method('getId')->willReturn(1);

    //     // $user2 = $this->createMock(User::class);
    //     // $user2->method('getId')->willReturn(2);

    //     $car->setUser($user2);

    //     $token = new UsernamePasswordToken($user, 'credentials', 'memory');

    //     $this->assertSame(-1, $voter->vote($token, $car, ['EDIT']));
    //}

    public function voterProvider()
    {
        $userOne = $this->createMock(User::class);
        $userOne->method('getId')->willReturn(1);
        
        return [
            [$userOne, 1],
            [null, -1],
        ];
    }
}



