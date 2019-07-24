<?php /** @noinspection ALL */

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(
        \Twig_Environment $twig ,
        MicroPostRepository $microPostRepository,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        FlashBagInterface $bag,
        AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->twig = $twig;
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->bag = $bag;
        $this->authorizationChecker = $authorizationChecker;
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
     * @Security("is_granted('edit' , microPost)" , message="Nemate pristup!")
     */
    public function edit(MicroPost $microPost , Request $request)
    {
        //$this->denyUnlessGranted('edit' , $microPost);
        //OVO KORITSTIMO AKO EXTENDAMO Controller


        //if(!$this->authorizationChecker->isGranted('edit' , $microPost)){
          //  throw new UnauthorizedHttpException();
        //}
        //OVO KORISTIM AKO Controller NIJE EXTEND-AN TE INJECTAMO AuthorizationCheckerInterface U __Consturct
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
     * @Security("is_granted('delete' , microPost)" , message="Nemate pristup")
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
     * @Security("is_granted('ROLE_USER')")
     */
    public function add(Request $request ,TokenStorageInterface $tokenStorage)
    {
        $user = $tokenStorage->getToken()->getUser();

        $microPost = new MicroPost();
        //$microPost->setTime(new \DateTime());
        $microPost->setUser($user);

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
     * @Route("/user/{username}" , name="micro_post_user")
     */
    public function userPosts(User $user)
    {
        $html = $this->twig->render('micro-post/index.html.twig' ,
            [
                // 'posts' =>$this->microPostRepository->findBy(['user' =>$user] , ['time' => 'DESC'])

                /*LAZY LOADING -> */ /*NECE BITI SORTIRANO */
                'posts' => $user ->getPosts()
            ]);
        return new Response($html);
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
