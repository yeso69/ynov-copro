<?php

namespace AppBundle\Form;

use AppBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')->add('description')->add('status')
            ->add('members', EntityType::class, array(
                'class' => 'AppBundle:Owner',
                'choice_label' => 'firstname',
                'label' => 'Concerned Owner(s)',
                'multiple' => true,
            ))
            ->add('status', ChoiceType::class, array(
                'choices' => array('In discussion' => Project::STATUS_IN_DISCUSSION, 'Waiting for execution' => Project::STATUS_WAINTING_FOR_EXECUTION, 'Done' => Project::STATUS_DONE)
            ));
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Project'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_project';
    }


}
