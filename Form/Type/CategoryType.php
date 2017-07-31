<?php
namespace Discutea\DForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'discutea.forum.category.form.name'))
            ->add('position')
            ->add('readAuthorisedRoles', ChoiceType::class, array(
                'label' => 'discutea.forum.category.permitions',
                'choices' => $options['roles'],
                'required'          => false,
            ))
        ;
    }
  
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Discutea\DForumBundle\Entity\Category',
            'roles' => null
        ));
    }
}
