<?php

namespace App\Form;

use App\Entity\Couteau;
use App\Entity\CouteauOutil;
use App\Entity\Outil;
use App\Repository\CouteauOutilRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CouteauOutilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('outil', EntityType::class, [
                'class' => Outil::class,
                'expanded' => false,
                'multiple' => true,
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CouteauOutil::class,
        ]);
    }
}