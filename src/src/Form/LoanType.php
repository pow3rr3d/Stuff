<?php

namespace App\Form;

use App\Entity\Loan;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class LoanType extends AbstractType
{
    private $security;
    private $user;
    private $em;

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
            ->add('product', ChoiceType::class, [
                "choices" => [
                    $this->em->getRepository(Product::class)->findBy(["loan" => $this->getParent()]) //Get Current entity to access to products
                ],
                'choice_label' => "name"
            ])
            ->add('loanedAt')
//            ->add('loanedBy')
            ->add('borrowedBy');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Loan::class,
        ]);
    }
}
