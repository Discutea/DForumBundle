<?php
namespace Discutea\DForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ForumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('image', UrlType::class, array(
                'required' => false,
            ))
            ->add('category', EntityType::class, array(
                'class' => 'DForumBundle:Category',
                'choice_label' => 'name',
            ))
            ->add('position')
            ->add('save', SubmitType::class)
        ;
    }

    public function getName()
    {
        return 'forum_new_forum';
    }
  
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Discutea\DForumBundle\Entity\Forum',
            'roles' => null
        ));
    }
}
