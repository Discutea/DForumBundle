<?php
namespace Discutea\DForumBundle\Form\Type\Remover;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityManager;

class RemoveCategoryType extends AbstractType
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
                'choices' => $this->getAllCategories(),
            ))
            ->add('purge', CheckboxType::class, array(
                'label'    => 'discutea.forum.category.removeall.label',
                'required' => false,
            ))
        ;
    }

    /**
     * 
     * Listing all forums order by categories
     * 
     * @return array categorie's list ordoned
     */
    private function getAllCategories() {
        $categories = $this->em->getRepository('DForumBundle:Category')->findBy(array(), array('position' => 'asc', ));

        $cats = array();
        foreach ($categories as $category) {
            $cats[$category->getName()] = $category->getId();  
        }

        return $cats;
    }

    public function getName()
    {
        return 'discutea.forum_remove_category';
    }
}
