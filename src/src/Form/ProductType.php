<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Subcategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class ProductType extends AbstractType
{
    private $em;

    private $choices;


    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;

        $this->choices = $this->em->getRepository(Subcategory::class)->findAll();

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('state', ChoiceType::class, [
                "choices" => [
                    "Perfect" => "Perfect",
                    "Good" => "Good",
                    "Average condition" => "Average condition",
                    "Bad condition" => "Bad condition"
                ]
            ])
            ->add('color')
            ->add('subcategory', ChoiceType::class, [
                "choices" => $this->choices,
                'choice_label' => "name",
                'group_by' => function ($choice, $key, $value) {
                    if ($choice->getCategory()) {
                        return $choice->getCategory()->getName();
                    }

                    return strtoupper($key);
                },
            ])
            ->
            add("imageFile", FileType::class, [
                "required" => false,
                "attr" => [
                    "class" => "form-control"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}