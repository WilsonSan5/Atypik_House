<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Habitats;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;





class HabitatsType_user extends AbstractType
{
    private function getUser(Security $user){

        $user = $user->getUser();
        $user_id = $user->getId();
        $user_role = $user->getRoles();
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
    
        $builder
            ->add('titre')
            ->add('adresse')
            ->add('image')
            ->add('categorie')
            ->add('prix',IntegerType::class,[
                'label' => 'Prix / par nuits'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Habitats::class,
        ]);
    }
}
