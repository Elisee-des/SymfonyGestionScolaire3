<?php

namespace App\Form\Inscription;

use App\Entity\Annee;
use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Trimestre;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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

class EditerInscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("annee", EntityType::class, [
                'class' => Annee::class,
                'label' => "Choisir l'annee accademique (*Obligatoire)"
            ])
            ->add("trimestre", EntityType::class, [
                'mapped' => false,
                'class' => Trimestre::class,
                'label' => "Choisir le trimestre d'inscription (*Obligatoire)"
            ])
            ->add("classe", EntityType::class, [
                'class' => Classe::class,
                'label' => "Choisir la classe de l'eleve (*Obligatoire)"
            ])
            ->add("nom_parent", TextType::class, [
                "mapped" => false,
                'label' => "Le nom du parent (*Obligatoire)"
            ])
            ->add("prenom_parent", TextType::class, [
                "mapped" => false,
                'label' => "Le prenom du parent (*Obligatoire)"
            ])
            ->add("numero_parent", NumberType::class, [
                "mapped" => false,
                'label' => "Le numero du parent (*Obligatoire)"
            ])
            ->add('password', RepeatedType::class, [
                "mapped" => false,
                "type" => PasswordType::class,
                "first_options" => [
                    "label" => "Nouveau mot de passe (*Obligatoire)",
                    'attr' => ['autocomplete' => 'new-password']
                ],
                "second_options" => [
                    "label" => "Repeter le mot de passe (*Obligatoire) ",
                    'attr' => ['autocomplete' => 'new-password']
                ],
                "invalid_message" => "Mot de passe non identique",
            ])
            ->add("email_parent", TextType::class, [
                "mapped" => false,
                'label' => "Email du parent (*Facultatif)"
            ])
            // ->add("")            
            ->add("nom", TextType::class, [
                'label' => "Nom de l'eleve (*Obligatoire)"
            ])
            ->add("prenom", TextType::class, [
                'label' => "Le prenom de l'eleve (*Obligatoire)"
            ])
            ->add("isRedoublant", ChoiceType::class, [
                'label' => "Est t-il un(e) redoublant(e) ? (*Obligatoire)",
                'choices' => [
                    "Non" => "1",
                    "Oui" => "2"
                ]
            ])
            ->add("ancienLycee", TextType::class, [
                'label' => "Le nom de l'ancien lyceé (*Obligatoire)"
            ])
            ->add("numero", NumberType::class, [
                'label' => "Le numero de l'eleve (*Facultatif)"
            ])
            ->add("email", EmailType::class, [
                'label' => "L'email de l'eleve (*Facultatif)"
            ])
            ->add("frais", NumberType::class, [
                'label' => "Le montant des frais de scolarité payé a l'inscription"
            ])
            ->add('rgpd', CheckboxType::class, [
                "label" => "Cette eleve inscript s'engage a ce soumettre au reglement interieur"
            ])

            ->add('Modification', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }
}
