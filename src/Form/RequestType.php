<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Caregiver;
use App\Entity\Examination;
use App\Entity\Request;
use App\Entity\Veterinary;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('veterinary', EntityType::class, [
                'class' => Veterinary::class,
                'choice_label' => function (Veterinary $vet) {
                    $user = $vet->getUser();
                    return $user->getName() . ' ' .
                        $user->getSurname() . ' (' .
                        $user->getEmail() . ')';
                },
            ])
            ->add('animal', EntityType::class, [
                'class' => Animal::class,
                'choice_label' => function (Animal $animal) {
                    return $animal->getName() . ' (' .
                        $animal->getSpecies() . ' ' .
                        $animal->getBreed() . ')';
                },
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Request::class,
        ]);
    }
}
