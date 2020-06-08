<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Competition;
use App\Entity\Discipline;
use App\Entity\Event;
use App\Entity\Field;
use App\Entity\Round;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('competition', EntityType::class,[
                "class" => Competition::class,
                "choice_label" => "name",
                "label" => 'Compétition',
                "required" => 'true'
            ])
            ->add('discipline', EntityType::class,[
                "class" => Discipline::class,
                "choice_label" => "name",
                "label" => 'Discipline',
                "required" => 'true'
            ])
            ->add('name', HiddenType::class)
            ->add('gender', null, ['label' => 'Genre'])
            ->add('category', EntityType::class,[
                "class" => Category::class,
                "choice_label" => "name",
                "label" => 'Catégorie',
                "required" => 'false'
            ])
            ->add('type', EntityType::class,[
                "class" => Type::class,
                "choice_label" => "name",
                "label" => 'Type',
                "required" => 'false'
            ])
            ->add('round', EntityType::class,[
                "class" => Round::class,
                "choice_label" => "name",
                "label" => 'Tour',
                "required" => 'false'
            ])
            ->add('phase', null, ['label' => 'Nombre de phases'])
            ->add('startAt', DateTimeType::class, ['label' => 'Heure de début'])
            ->add('duration', null, ['label' => 'Durée d\'une rencontre'])
            ->add('breakRest', null, ['label' => 'Durée pause'])
            ->add('meridianBreakHour', DateTimeType::class, ['label' => 'Heure de début de la pause méridienne'])
            ->add('meridianBreak', null, ['label' => 'Durée pause méridienne'])
            ->add('nbrFields', null, ['label' => 'Nombre de terrains'])
            ->add('poule', null, ['label' => 'Poules ?'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
