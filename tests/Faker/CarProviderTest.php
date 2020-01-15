<?php
namespace App\Tests\Faker;

use App\Faker\CarProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CarProviderTest extends WebTestCase
{
    /**
     *@dataProvider carburantProvider
     */
    public function testCarburantContains($carburant)
    {
        $this->assertContains($carburant, CarProvider::CARBURANT);
        // $this->assertContains('electrique', CarProvider::CARBURANT);//sans provider
        // $this->assertContains('diesel', CarProvider::CARBURANT);
        // $this->assertContains('essence', CarProvider::CARBURANT);
       
    }

    /**
     *@dataProvider colorProvider
     */
    public function testColorContains($color)
    {
        $this->assertContains($color, CarProvider::CARBURANT);
        
       
    }

    public function testCarburantNotContains()
    {
        $this->assertNotContains('ethanol', CarProvider::CARBURANT);
    }

    public function carburantProvider()// pour eviter de dupliquer les assertContains manuellement
    {
        return [
            ['electrique'],
            ['diesel'],
            ['essence'],
        ];
    }

    public function colorProvider()// pour eviter de dupliquer les assertContains manuellement
    {
        return [
            ['rouge'],
            ['noir'],
            ['vert'],
        ];
    }
}