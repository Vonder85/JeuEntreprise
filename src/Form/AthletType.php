<?php

namespace App\Form;

use App\Entity\Athlet;
use App\Entity\Company;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AthletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Nom',
                "required" => 'true'])
            ->add('firstname', null, ['label' => 'Prénom',
                "required" => 'true'])
            ->add('dateBirth', BirthdayType::class, ['label' => 'Date de naissance',
                "required" => 'true'])
            ->add('reference', null, ['label' => 'Référence'])
            ->add('company', EntityType::class,[
                "class" => Company::class,
                "choice_label" => "name",
                "label" => 'Entreprise',
                "required" => 'true'
            ])
            ->add('country', null, ['label' => 'Pays'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Athlet::class,
        ]);
    }
}
