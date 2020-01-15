<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Image;
use App\Form\CarType;
use App\Entity\Keyword;
use App\Services\ImageHandler;
use Doctrine\ORM\EntityManager;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CarRepository $carRepository)
    {
        $cars = $carRepository->findAll();

        $car = $carRepository->find(27);

        return $this->render('home/index.html.twig', [
            'cars' => $cars,
        ]);
    }



    /**
     * @Route("car/add", name="add")
     */
    public function add(EntityManagerInterface $manager, Request $request, ImageHandler $handler)
    {

        $path = $this->getParameter('kernel.project_dir') .'/public/images';
        $form = $this->createForm(CarType::class, null, ['path' =>$path]);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){

            $path = $this->getParameter('kernel.project_dir'). '/public/images';
            $car = $form->getData();//recuperer le formulaire soumis sous forme dojet car
            $user = $this->getUser();// ca rend l'utilisateur connecter

            $car->setUser($user);//utilistaru que jai recuperer
            
           
            $manager->persist($car);
            $manager->flush();

            $this->addFlash(
                'notice',
                'Super ! une nouvelle voiture a ete ajoutée'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('home/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("car/edit/{id}", name="edit")
     */
    public function edit(Car $car, EntityManagerInterface $manager, Request $request )
    {
        $path = $this->getParameter('kernel.project_dir') .'/public/images';
        $form = $this->createForm(CarType::class, $car, ['path' =>$path]);
        $this->denyAccessUnlessGranted('EDIT', $car);
         $form = $this->createForm(CarType::class, $car );// recuperer car dans la base de dnne


        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            $path = $this->getParameter('kernel.project_dir') .'/public/images';

           
            $manager->flush();

            $this->addFlash(
                'notice',
                'Super ! une nouvelle voiture a ete amodifier'
            );

            return $this->redirectToRoute('home');
        }

        
        // $car->setModel("Ferrari");
        //$manager->flush($car);


        return $this->render('home/edit.html.twig', [
            'car' => $car,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("car/delete/{id}", name="delete")
     */
    public function delete(Car $car, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('DELETE', $car);
        $manager->remove($car);
        $manager->flush($car);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(Car $car)
    {

        // $car = $this->getDoctrine()->getRepository(Car::class)->find($id);

        return $this->render('home/show.html.twig', [
            'car' => $car
        ]);
    }

    /**
     * @Route("delete/keyword/{id}", name="delete_keyword",
     * methods={"POST"},
     * condition="request.headers.get('X-Requested-With') matches '/XMLHttpRequest/i'")
     */
    public function deleteKeyword(Keyword $keyword, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($keyword);
        $entityManager->flush();

        return new JsonResponse();
        //if($request->isXmlHttpRequest())
    }
}
