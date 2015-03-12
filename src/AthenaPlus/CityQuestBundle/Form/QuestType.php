<?php

namespace AthenaPlus\CityQuestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('nameOrganisation')
            ->add('fullAddress')
            ->add('contactPerson')
            ->add('emailAddress')
            ->add('telephoneNumber')
            ->add('abstract')
            ->add('averageDuration', 'choice', array(
                'choices'   => array('30 minutes' => '< 30 minutes',
                                     '1 hour' => '< 1 hour',
                                     '1-2 hours' => '1-2 hours',
                                     'half day' => 'half day',
                                     'full day' => 'full day',
                ),
            ))
            ->add('disclaimer')
            ->add('media', 'sonata_media_type', array(
                'provider' => 'sonata.media.provider.image',
                'context'  => 'default'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AthenaPlus\CityQuestBundle\Entity\Quest'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'athenaplus_cityquestbundle_quest';
    }
}
