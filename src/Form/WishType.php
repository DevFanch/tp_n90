<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Your Idea',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Please describe it!',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('author', TextType::class, [
                'label' => 'Your username',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Category',
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => '-- Choose a category --'
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/*',
                ],
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Let\'s Go! 🎉',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
        ;

        // Add event to display delete checkbox if exist
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                // Get data from form
                $wish = $event->getData();
                // To add delete checkbox
                $form = $event->getForm();
                // Test if data exist
                if ($wish && $wish->getImage()) {
                    $form->add('deleteImage', CheckboxType::class, [
                        'required' => false,
                        'mapped' => false,
                    ]);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
