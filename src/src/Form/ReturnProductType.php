<?php


namespace App\Form;


use App\Entity\Product;
use App\Entity\Subcategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReturnProductType extends AbstractType
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
            ->add('description', TextType::class, [
                'disabled' =>'true'
            ])
            ->add('state', ChoiceType::class, [
                "choices" => [
                    "Perfect" => "Perfect",
                    "Good" => "Good",
                    "Average condition" => "Average condition",
                    "Bad condition" => "Bad condition"
                ],
                "required" => true
            ])
            ->add('color', TextType::class, [
                'disabled' =>'true'
            ])
            ->add('subcategory', ChoiceType::class, [
                'disabled' =>'true',
                "choices" => [
                    $this->em->getRepository(Subcategory::class)->findAll()
                ],
                'choice_label' => "name"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

}