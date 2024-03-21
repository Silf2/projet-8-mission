<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => "Titre de la tâche"])
            ->add('description', TextareaType::class, [
                'label' => "Description",
                "required" => false
                ])
            ->add('deadline', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd', 
                "required" => false
            ])
            ->add('status', ChoiceType::class, [
                'label' => "Status",
                'choices' => [
                    'To Do' => 'To Do',
                    'Doing' => 'Doing',
                    'Done' => 'Done',
                ],
            ])
            ->add('user', ChoiceType::class, [
                'label' => "Membre",
                'choices' => array_merge($options['users']->toArray()),
                'choice_label' => function($user) {
                    return $user->getFirstName() . ' ' . $user->getLastName();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'users' => [],
        ]);
    }
}
