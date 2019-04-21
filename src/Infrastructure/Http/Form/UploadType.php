<?php
declare(strict_types=1);

namespace Radarlog\S3Uploader\Infrastructure\Http\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UploadType extends AbstractType
{
    // phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'attr' => ['accept' => 'image/*'],
                'label' => 'Image File',
                'mapped' => false,
                'multiple' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Upload',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'empty_data' => static function (FormInterface $form) {
                /** @var UploadedFile $file */
                return $form->get('image')->getData();
            },
        ]);
    }
}