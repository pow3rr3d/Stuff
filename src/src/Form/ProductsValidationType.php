<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Subcategory;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsValidationType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'disabled' =>'true'
            ])
            ->add('previousState', TextType::class, [
                'disabled' => 'true'
            ])
            ->add('isNormalWear', CheckboxType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
