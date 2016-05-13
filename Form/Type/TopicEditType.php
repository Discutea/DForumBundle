<?php

namespace Discutea\DForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Discutea\DForumBundle\Form\Type\Model\AbstractTopicType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TopicEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('forum', EntityType::class, array(
                'class' => 'DForumBundle:Forum',
                'choice_label' => 'translations[en].name',
            ))
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
