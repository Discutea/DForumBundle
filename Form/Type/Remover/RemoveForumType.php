<?php
namespace Discutea\DForumBundle\Form\Type\Remover;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityManager;

class RemoveForumType extends AbstractType
{
    /**
     *
     * @var type EntityManager
     */
    protected $em;

    /**
     * 
     * @param EntityManager $em
     */    
    public function __construct (EntityManager $em) {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                
            ->add('movedTo', ChoiceType::class, array(
                'choices' => $this->getAllForums(),
            ))
                
            ->add('purge', CheckboxType::class, array(
                'label'    => 'discutea.forum.forum.removeall.label',
                'required' => false,
            ))
        ;
    }

    /**
     * 
     * Listing all forums order by categories
     * 
     * @return array forum's list ordoned
     */
    private function getAllForums() {
        $categories = $this->em->getRepository('DForumBundle:Category')->findBy(array(), array('position' => 'asc', ));
        $fors = array();

        foreach ($categories as $category) {
            $tmpForums = array();
            $forums = $category->getForums();

            foreach ($forums as $forum) {
                $tmpForums[$forum->getName()] = $forum->getId();
            }

            $fors[$category->getName()] = $tmpForums;
        }
        
        return $fors;
    }
}
