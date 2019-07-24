<?php
/**
 * Created by PhpStorm.
 * User: DarkoKlisuric
 * Date: 23.7.2019.
 * Time: 14:05
 */

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username' , TextType::class ,[
                        'label' => 'Korisnicko ime'
        ])
                ->add('email' , EmailType::class)
                ->add('plainPassword' , RepeatedType::class, [
                    'type' =>PasswordType::class,
                    'first_options' =>['label' => 'Lozinka'],
                    'second_options' =>['label' =>'Ponovljena lozinka']
                ])
                ->add('fullName' , TextType::class ,[
                    'label' => 'Ime i prezime'
                ])
                ->add('termsAgreed' , CheckboxType::class ,
                    [
                        'mapped' => false,
                        'constraints' => new IsTrue(),
                        'label' =>'Slazem se sa uvijetima koristenja'
                    ])
                ->add('Registriraj se' , SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' =>User::class
        ]);
    }

}