<?php

namespace App\Form;

use App\Entity\Athlet;
use App\Entity\TeamCreated;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamCreatedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('athlet',EntityType::class,[
                'class'=>Athlet::class,
                'label'=> 'Sélectionner un athlète',
                'choice_label'=> 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TeamCreated::class,
        ]);
    }
}
