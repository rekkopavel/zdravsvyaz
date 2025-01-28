<?php
declare(strict_types=1);

namespace App\Form;


use App\Entity\Paste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Title'])
            ->add('content', TextareaType::class, [
                'attr' => ['rows' => 15]
            ])
            ->add('access', ChoiceType::class, [
                'choices' => [
                    'Public' => '1',
                    'Unlisted' => '0'
                ]
            ])
            ->add('lang', ChoiceType::class, [
                'choices' => [
                    'Plain Text' => null,
                    'PHP' => 'php',
                    'JavaScript' => 'javascript',
                    'Python' => 'python'
                ],
                'required' => false
            ])
            ->add('expiration', ChoiceType::class, [
                'choices' => [
                    '1 Hour' => '1 hour',
                    '1 Day' => '1 day',
                    '1 Week' => '1 week',
                    '1 Month' => '1 month',
                    'No Limit' => null
                ],
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paste::class,
            'empty_data' => new Paste(),
        ]);
    }
}
