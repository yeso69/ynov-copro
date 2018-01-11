<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChargeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('subject')->add('cost')->add('dueDate')->add('status',ChoiceType::class, array(
            'choices'=>["Paid"=>True, "Due"=>False]
        ))
            ->add('document', FileType::class , array('label' => 'Document (PDF file)',
                'required'=>False, 'data_class' => null))
            ->add('contract');
        /*->add('payment', EntityType::class, array(
                'class'=>'AppBundle\Entity\Payment',
                'label'=>'Payments done',
                'required'=>False));*/
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Charge'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_charge';
    }


}
