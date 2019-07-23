<?php /** @noinspection ALL */

namespace App\Controller;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/micro-post")
 */
class MicroPostController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var FlashBagInterface
     */
    private $bag;

    public function __construct(
        \Twig_Environment $twig ,
        MicroPostRepository $microPostRepository,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        FlashBagInterface $bag)
    {
        $this->twig = $twig;
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->bag = $bag;
    }


    /**
     * @Route("/" , name="micro_post_index")
     */
    public function index()
    {
        $html = $this->twig->render('micro-post/index.html.twig' ,
            [
                'posts' =>$this->microPostRepository->findBy([] , ['time' => 'DESC'])
            ]);
        return new Response($html);
    }

    /**
     * @param MicroPost $microPost
     * @param Request $request
     * @Route("/edit/{id}" , name="micro_post_edit")
     */
    public function edit(MicroPost $microPost , Request $request)
    {
        $form = $this->formFactory->create(
            MicroPostType::class,
            $microPost
        );

        $form->handleRequest($request);
        $microPost->setTime(new \DateTime());

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('micro_post_index'));
        }
        return new Response(
            $this->twig->render('micro-post/add.html.twig' ,
                ['form' =>$form->createView()])
        );
    }

    /**
     * @param MicroPost $microPost
     * @Route("delete/{id}" , name="micro_post_delete")
     */
    public function delete(MicroPost $microPost)
    {
        $this->entityManager->remove($microPost);
        $this->entityManager->flush();
        $this->bag->add('notice' , 'Micro post uspjesno obrisan!');


        return new RedirectResponse($this->router->generate('micro_post_index'));

    }
    /**
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @Route("/add" , name="micro_post_add")
     */
    public function add(Request $request)
    {
        $microPost = new MicroPost();
        $microPost->setTime(new \DateTime());

        $form = $this->formFactory->create(MicroPostType::class , $microPost);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('micro_post_index'));
        }
        return new Response(
            $this->twig->render('micro-post/add.html.twig' ,
                ['form' =>$form->createView()])
        );
    }

    /**
     * @Route("/{id}" , name="micro_post_post")
     */
    public function post(MicroPost $post)
    {
        return new Response(
            $this->twig->render(
                'micro-post/post.html.twig',
                    [
                        'post' =>$post
                    ]
            )
        );
    }
}
