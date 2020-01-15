<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\City;
use App\Entity\Image;
use App\Form\ImageType;
use App\Form\KeywordType;
use App\Faker\CarProvider;


use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model', TextType::class, [

            ])
            ->add('price', NumberType::class, [
            ])
            ->add('image', ImageType::class, ['label' =>false])//pour enlever image ecrit en petit
            ->add('color', ChoiceType::class, [
                'label' => false,
                'choices' =>
                    array_combine(CarProvider::COLOR, CarProvider::COLOR)
            ])
            ->add('carburant', ChoiceType::class, [
                'label' => false,
                'choices' =>
                    array_combine(CarProvider::CARBURANT, CarProvider::CARBURANT)
            ])
            ->add('keywords', CollectionType::class, [
                'entry_type' => KeywordType::class,
                'allow_add' => true, 
                'allow_delete' => true, 
                'by_reference' => false,// il va forcer symfony a appeller la methode cote car entity addKeywords
            ])
            ->add('cities', EntityType::class, [
                'label' => 'ville',
                'class' => City::class,
                'choice_label' => 'name',
                //'mutliple' => true,
                'expanded' =>false,

            ])
            
        ;
                // pour ajouter une voiture sans image 
        $builder->addEventListener(FormEvents::POST_SUBMIT,
        function (FormEvent $event) use ($options) {
            $car = $event->getData();

            if (null === $car->getImage()->getFile()) {
                $car->setImage(null);
                return;
            }
             //recupere l'image
             $image = $car->getImage();

             $image->setPath($options['path']);// il va contenir sur ce que je vais lui passer au contoller au add du parametre $form

            //setPath
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
            'path' => null,
        ]);
    }
}
