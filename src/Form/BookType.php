<?php
/**
 * Book type.
 */

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Cover;
use App\Entity\Language;
use App\Entity\Publisher;
use App\Entity\Status;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BookType.
 */
class BookType extends AbstractType
{
    /**
     * Tags data transformer.
     *
     * @var \App\Form\DataTransformer\TagsDataTransformer
     */
    private $tagsDataTransformer;

    /**
     * BookType constructor.
     *
     * @param \App\Form\DataTransformer\TagsDataTransformer $tagsDataTransformer Tags data transformer
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array                                        $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => 'label_title',
                'required' => true,
                'attr' => ['max_length' => 64],
            ]
        );

        $builder->add(
            'date',
            IntegerType::class,
            [
                'label' => 'label_year',
                'required' => true,
            ]
        );

        $builder->add(
            'pages',
            IntegerType::class,
            [
                'label' => 'label_pages',
                'required' => true,
                'attr' => ['max_length' => 64],
            ]
        );

        $builder->add(
            'description',
            TextType::class,
            [
                'label' => 'label_description',
                'required' => true,
                'attr' => ['max_length' => 500],
            ]
        );

        $builder->add(
            'image',
            TextType::class,
            [
                'label' => 'label_image',
                'required' => true,
                'attr' => ['max_length' => 500],
            ]
        );

        $builder->add(
            'author',
            EntityType::class,
            [
                'class' => Author::class,
                'choice_label' => function ($author) {
                    return $author->getName();
                },
                'label' => 'label_author',
                'placeholder' => 'label_none',
                'required' => true,
            ]
        );

        $builder->add(
            'cover',
            EntityType::class,
            [
                'class' => Cover::class,
                'choice_label' => function ($cover) {
                    return $cover->getName();
                },
                'label' => 'label_cover',
                'placeholder' => 'label_none',
                'required' => true,
            ]
        );

        $builder->add(
            'publisher',
            EntityType::class,
            [
                'class' => Publisher::class,
                'choice_label' => function ($publisher) {
                    return $publisher->getName();
                },
                'label' => 'label_publisher',
                'placeholder' => 'label_none',
                'required' => true,
            ]
        );

        $builder->add(
            'language',
            EntityType::class,
            [
                'class' => Language::class,
                'choice_label' => function ($language) {
                    return $language->getName();
                },
                'label' => 'label_language',
                'placeholder' => 'label_none',
                'required' => true,
            ]
        );

        $builder->add(
            'status',
            EntityType::class,
            [
                'class' => Status::class,
                'choice_label' => function ($status) {
                    return $status->getName();
                },
                'label' => 'label_status',
                'placeholder' => 'label_none',
                'required' => true,
            ]
        );

        $builder->add(
            'category',
            EntityType::class,
            [
                'class' => Category::class,
                'choice_label' => function ($category) {
                    return $category->getName();
                },
                'label' => 'label_category',
                'placeholder' => 'label_none',
                'required' => true,
            ]
        );

        $builder->add(
            'tag',
            TextType::class,
            [
                'label' => 'label_tags',
                'required' => false,
                'attr' => ['max_length' => 128],
            ]
        );

        $builder->get('tag')->addModelTransformer(
            $this->tagsDataTransformer
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Book::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'book';
    }
}
