<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     *@Route("/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        return $this->render('dashboard.html.twig', [
            'cars' => $this->getUser()->getCars(),
        ]);
    }
}
