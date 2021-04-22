<?php

namespace flexycms\FlexySEOBundle\Form;

use flexycms\FlexySEOBundle\EntityRequest\SEOSettingRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SeoSettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder

                ->add('route', HiddenType::class)

                ->add('title', TextType::class, array(
                    'label' => 'Заголовок',
                    'required' => false,
                    'empty_data' => '',
                ))
                ->add('description', TextType::class, array(
                    'label' => 'Описание',
                    'required' => false,
                    'empty_data' => '',
                ))
                ->add('keywords', TextType::class, array(
                    'label' => 'Ключевые слова',
                    'required' => false,
                    'empty_data' => '',
                ))
                ->add('ogImage', TextType::class, array(
                    'label' => 'Картинка для OG',
                    'required' => false,
                    'empty_data' => '',
                ))
                ->add('ogTitle', TextType::class, array(
                    'label' => 'Заголовок для OG',
                    'required' => false,
                    'empty_data' => '',
                ))
                ->add('ogDescription', TextType::class, array(
                    'label' => 'Описание для OG',
                    'required' => false,
                    'empty_data' => '',
                ))
                ->add('save', SubmitType::class, array(
                    'label' => '<i class="fas fa-save"></i><br>Сохранить',
                    'label_html' => true,
                    'attr' => [
                        'class' => 'btn btn-success',
                    ],
                ))
                ->add('apply', SubmitType::class, array(
                    'label' => '<i class="fas fa-check"></i><br>Применить',
                    'label_html' => true,
                    'attr' => [
                        'class' => 'btn btn-success',
                    ],
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SEOSettingRequest::class,
        ]);
    }

}
