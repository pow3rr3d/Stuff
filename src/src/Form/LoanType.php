<?php

namespace App\Form;

use App\Entity\Loan;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class LoanType extends AbstractType
{
    private $security;
    private $user;
    private $em;
    private $loan;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->user = $this->security->getUser();
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('loanedAt', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('borrowedBy', EntityType::class, [
                "class" => User::class,
                'choice_label' => function ($user) {
                    return $user->getName();
                },
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->createQueryBuilder('u')
                        ->where('u.id != :id')
                        ->setParameter('id', $this->user->getId());
                },
            ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $products = $this->em->getRepository(Product::class)->findBy(["loan" => $event->getData()]);
            foreach ($products as $product) {
                $product->setLoan(null);
            }
        });


        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $this->loan = $this->em->getRepository(Loan::class)->findOneBy(["id" => $event->getData()->getId()]);
            if ($this->loan) {
                $event->getForm()->add('product', EntityType::class, [
                    "class" => Product::class,
                    'choice_label' => function ($product) {
                        return $product->getName();
                    },
                    'attr' => [
                        'class' => 'chosen-select'
                    ],
                    'multiple' => true,
                    'query_builder' => function (ProductRepository $productRepository) {
                        return $productRepository->createQueryBuilder('p')
                            ->andWhere('p.loan IS NULL OR p.loan = :loan')
                            ->andWhere('p.user = :user')
                            ->setParameter('loan', $this->loan)
                            ->setParameter('user', $this->user);
                    },
                ]);
            } else {
                $event->getForm()->add('product', EntityType::class, [
                    "class" => Product::class,
                    'choice_label' => function ($product) {
                        return $product->getName();
                    },
                    'attr' => [
                        'class' => 'chosen-select'
                    ],
                    'multiple' => true,
                    'query_builder' => function (ProductRepository $productRepository) {
                        return $productRepository->createQueryBuilder('p')
                            ->andWhere('p.loan IS NULL')
                            ->andWhere('p.user = :user')
                            ->setParameter('user', $this->user);
                    },
                ]);
            }
        });
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Loan::class,
        ]);
    }
}
