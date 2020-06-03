<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Participation;
use App\Repository\ParticipantRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipationAthletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('participant', EntityType::class,[
                "class" => Participant::class,
                "query_builder" => function(ParticipantRepository $pr){
                    return $pr->createQueryBuilder('p')
                        ->andWhere('p.athlet != :null')
                        ->setParameter('null', "null");
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
