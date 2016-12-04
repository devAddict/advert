<?php

namespace DA\PlatformBundle\Form;


use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('date',           DateType::class)
        ->add('title',          TextType::class)
        ->add('content',        TextareaType::class)
        ->add('author',         TextType::class)
        ->add('email',          EmailType::class)
            
        ->add('image', ImageType::class)
        ->add('categories', EntityType::class, array(
           'class'    => 'DAPlatformBundle:Category',
           'choice_label'     => 'name',
           'multiple'  => true,
        ))
        ->add('save', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $advert = $event->getData();


            if ($advert === null) {
                return;
            }
            
            if (!$advert->getPublished() || null === $advert->getId()) {
                $event->getForm()->add('published', CheckboxType::class, array('required' => false));
            }else{
                $event->getForm()->remove('published');
            }
        });

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
            'data_class' => 'DA\PlatformBundle\Entity\Advert'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'da_platformbundle_advert';
    }


}
