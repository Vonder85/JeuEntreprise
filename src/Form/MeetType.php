<?php

namespace App\Form;

use App\Entity\Field;
use App\Entity\Meet;
use App\Entity\Participation;
use App\Entity\Round;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('participation', EntityType::class,[
                "class" => Participation::class,
                "choice_label" => "participant.name",
                "label" => 'Participant',
                "required" => 'true'
            ])
            ->add('round', EntityType::class,[
                "class" => Round::class,
                "choice_label" => "name",
                "label" => 'Round',
                "required" => 'false'
            ])
            ->add('field', EntityType::class,[
                "class" => Field::class,
                "choice_label" => "name",
                "label" => 'Field',
                "required" => 'false'
            ])
            ->add('match')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Meet::class,
        ]);
    }
}
