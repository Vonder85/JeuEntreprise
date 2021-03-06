<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Participation;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('participant', EntityType::class,[
                "class" => Participant::class,
                "query_builder" => function(ParticipantRepository $pr){
                return $pr->createQueryBuilder('p')
                    ->andWhere('p.team != :null')
                    ->setParameter('null', "null")
                    ->orderBy('p.name', 'asc');
                },
                "choice_label" => "name",
                "label" => 'Participant',
                "required" => 'false'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participation::class,
        ]);
    }
}
