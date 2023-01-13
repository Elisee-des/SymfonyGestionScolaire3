<?php

namespace App\Form\Administration;

use App\Entity\AdministrationSite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class sousfonctionnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('soustitre')
            ->add('fonctionnement')
            ->add('sousfonctionnement')
            ->add('titre1')
            ->add('soustitre1')
            ->add('titre2')
            ->add('soustitre2')
            ->add('titre3')
            ->add('soustitre3')
            ->add('description1')
            ->add('sousdescription1')
            ->add('description2')
            ->add('sousdescription2')
            ->add('contact')
            ->add('localisation')
            ->add('quiSommeNous')
            ->add('Modifié', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdministrationSite::class,
        ]);
    }
}
