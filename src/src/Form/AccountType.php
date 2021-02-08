<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class AccountType extends AbstractType
{
    private $user;
    private $security;
    private $em;
    private $encoder;

    public function __construct(Security $security, EntityManagerInterface $em,UserPasswordEncoderInterface $encoder)
    {
        $this->security = $security;
        $this->user = $this->security->getUser();
        $this->em = $em;
        $this->encoder = $encoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('password', PasswordType::class, [
                "required" => false
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $user = $event->getData();

            if (empty($user["password"])) {
                $user["password"] = $this->em->getRepository(User::class)->findOneBy(["email" => $user['email']])->getPassword();
            }
            else{
                $user["password"] = $this->encoder->encodePassword($this->em->getRepository(User::class)->findOneBy(["email" => $user['email']]), $user["password"]);
            }

            $event->setData($user);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
