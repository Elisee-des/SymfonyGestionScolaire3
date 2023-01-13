<?php

namespace App\Form\Inscription;

use App\Entity\Annee;
use App\Entity\Classe;
use App\Entity\Trimestre;
use App\Repository\TrimestreRepository;
use Doctrine\DBAL\Platforms\TrimMode;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InscriptionType extends AbstractType
{
    private TrimestreRepository $trimestreRepository;

    public function __construct(TrimestreRepository $trimestreRepo)
    {
        $this->trimestreRepository = $trimestreRepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("annee", EntityType::class, [
                "mapped" => false,
                'class' => Annee::class,
                'label' => "Choisir l'anneé (*Obligatoire)"
            ])
            ->add("trimestre", EntityType::class, [
                "mapped" => false,
                'class' => Trimestre::class,
                "query_builder" => $this->trimestreRepository, 
                'label' => "Choisir le trimesrte (*Obligatoire)"
            ])
            ->add("nomParent", TextType::class, [
                "mapped" => false,
                'label' => "Le nom du parent (*Obligatoire)"
            ])
            ->add("prenomParent", TextType::class, [
                "mapped" => false,
                'label' => "Le prenom du parent (*Obligatoire)"
            ])
            ->add("numeroParent", NumberType::class, [
                "mapped" => false,
                'label' => "le numero du parent (*Obligatoire)"
            ])
            ->add('password', RepeatedType::class, [
                "mapped" => false,
                "type" => PasswordType::class,
                "first_options" => [
                    "label" => "Nouveau mot de passe (*Obligatoire)"
                ],
                "second_options" => [
                    "label" => "Repeter le mot de passe (*Obligatoire) "
                ],
                "invalid_message" => "Mot de passe non identique",
                "constraints" => [
                    new NotBlank()
                ]
            ])
            ->add("emailParent", TextType::class, [
                "mapped" => false,
                'label' => "email du parent (*Facultatif)"
            ])
            // ->add("")            
            ->add("nomEleve", TextType::class, [
                "mapped" => false,
                'label' => "Nom de l'eleve (*Obligatoire)"
            ])
            ->add("prenomEleve", TextType::class, [
                "mapped" => false,
                'label' => "Le prenom de l'eleve (*Obligatoire)"
            ])
            ->add("redoublant", ChoiceType::class, [
                "mapped" => false,
                'label' => "Est t-il un(e) redoublant(e) ? (*Obligatoire)",
                'choices' => [
                    "Oui" => "1",
                    "Non" => "2"
                ]
            ])
            ->add("ancienLycee", TextType::class, [
                "mapped" => false,
                'label' => "Le nom de l'ancien lyceé (*Obligatoire)"
            ])
            ->add("classe", EntityType::class, [
                "mapped" => false,
                'class' => Classe::class,
                'label' => "Choisir la classe de l'eleve (*Obligatoire)"
            ])
            ->add("numeroEleve", NumberType::class, [
                "mapped" => false,
                'label' => "le numero de l'eleve (*Facultatif)"
            ])
            ->add("emailEleve", EmailType::class, [
                "mapped" => false,
                'label' => "l'email de l'eleve (*Facultatif)"
            ])
            ->add("frais", NumberType::class, [
                "mapped" => false,
                'label' => "Le montant des frais de scolarité payé a l'inscription"
            ])
            ->add("Inscription", SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
