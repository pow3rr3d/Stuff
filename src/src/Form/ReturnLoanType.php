<?php

namespace App\Form;

use App\Entity\Loan;
use App\Entity\Product;
use App\Entity\Subcategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReturnLoanType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder
                ->add("product", CollectionType::class, [
                    "entry_type" => ReturnProductType::class
                ]);

//            ->add('name')
//            ->add('description')
//            ->add('state', ChoiceType::class, [
//                "choices" => [
//                    "Perfect" => "Perfect",
//                    "Good" => "Good",
//                    "Average condition" => "Average condition",
//                    "Bad condition" => "Bad condition"
//                ]
//            ])
//            ->add('color')
//            ->add('subcategory', ChoiceType::class, [
//                "choices" => [
//                    $this->em->getRepository(Subcategory::class)->findAll()
//                ],
//                'choice_label' => "name"
//            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Loan::class,
        ]);
    }
}
