<?php

namespace AppBundle\Form;

use AppBundle\AppBundle;
use AppBundle\Entity\Charge;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('amount')->add('contract')->add('owner')->add('date')
            ->add('document',FileType::class, array('label' => 'Document (PDF file)', 'required'=>False, 'data_class' => null))
            ->add('type', ChoiceType::class, ["choices"=>['Check'=>'Check', 'Transfer'=>'Transfer'], "label"=>"Payment type"])
            ->add('charge', EntityType::class, ["required"=>true, "class"=>"AppBundle:Charge", "label"=>"Charge"])
            ->add('done', ChoiceType::class, ['choices'=>['Yes'=>true, 'No'=>false]]);
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Payment'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_payment';
    }


}
