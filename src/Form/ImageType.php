<?php


namespace App\Form;


use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false
            ])
            ->add('alt', TextType::class, [
                'label' => 'Attribut Alt',
                'required' => true
            ])
            ->add('title', TextType::class, [
                'label' => 'Attribut Title',
                'required' => false
            ])
            ->add('width', IntegerType::class, [
                'label' => 'Largeur',
                'required' => false
            ])
            ->add('height', IntegerType::class, [
                'label' => 'Hauteur',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}