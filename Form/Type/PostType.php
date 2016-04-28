<?php

namespace Discutea\DForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Discutea\DForumBundle\Form\Type\Model\AbstractPostType;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ($options['preview'] === true) {
            $builder->add('preview', SubmitType::class);
        }
        
        $builder->add('save', SubmitType::class);
    }
    
    public function getParent()
    {
        return AbstractPostType::class;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'preview' => false
        ));
    }
}
