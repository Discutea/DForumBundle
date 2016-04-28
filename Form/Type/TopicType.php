<?php

namespace Discutea\DForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Discutea\DForumBundle\Form\Type\Model\AbstractTopicType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class TopicType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, array('mapped' => false))
            ->add('save', SubmitType::class)
        ;
    }
    
    public function getName()
    {
        return 'forumbundle_topic';
    }
  
    public function getParent()
    {
        return AbstractTopicType::class;
    }
}
