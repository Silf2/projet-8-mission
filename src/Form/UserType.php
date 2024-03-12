<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, ['label' => "Prénom"])
            ->add('last_name', TextType::class, ['label' => "Nom"])
            ->add('email', EmailType::class, ['label' => 'E-mail'])
            ->add('join_on', DateType::class, [
                'label' => "Date d'Entrée",
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('status', TextType::class, ['label' => 'Statut'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
