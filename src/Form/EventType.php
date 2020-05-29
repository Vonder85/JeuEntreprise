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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
                "label" => 'Competition',
                "required" => 'true'
            ])
            ->add('discipline', EntityType::class,[
                "class" => Discipline::class,
                "choice_label" => "name",
                "label" => 'Discipline',
                "required" => 'true'
            ])
            ->add('name')
            ->add('gender')
            ->add('category', EntityType::class,[
                "class" => Category::class,
                "choice_label" => "name",
                "label" => 'Category',
                "required" => 'false'
            ])
            ->add('type', EntityType::class,[
                "class" => Type::class,
                "choice_label" => "name",
                "label" => 'Type',
                "required" => 'false'
            ])
            ->add('meridianBreak')
            ->add('duration')
            ->add('breakRest')
            ->add('nbrFields')
            ->add('published')
            ->add('startAt', DateTimeType::class)


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
