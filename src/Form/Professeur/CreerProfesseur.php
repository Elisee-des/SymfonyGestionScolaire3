<?php

namespace App\Form\Professeur;

use App\Entity\Matiere;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreerProfesseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('numero', NumberType::class)
            ->add('password', RepeatedType::class, [
                "mapped" => true,
                "type" => PasswordType::class,
                "first_options" => [
                    "label" => "Nouveau mot de passe",
                    'attr' => ['autocomplete' => 'new-password']
                ],
                "second_options" => [
                    "label" => "Repeter le mot de passe ",
                    'attr' => ['autocomplete' => 'new-password']
                ],
                "invalid_message" => "Mot de passe non identique",
                "constraints" => [
                    new NotBlank()
                ]
            ])
            ->add('email', EmailType::class)
            ->add('matiere', EntityType::class, [
                "class" => Matiere::class,
                'query_builder' => function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('m')->orderBy('m.nom', 'DESC');
                },
                "label" => "Choisir la matiere du professeur",
            ])
            ->add('Creer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
