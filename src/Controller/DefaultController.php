<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\AddPostType;
use App\Repository\PostRepository;
use Doctrine\ORM\Cache\EntityCacheEntry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    // главная страница
//Главная
//На главной нужно выводить последние 3 поста в порядке убывания даты публикации.
//Для каждого поста выводить заголовок, дату публикации, содержимое первого абзаца из текста и ссылку
//"Читать дальше", которая будет вести на страницу поста.
//Вверху сделать ccылку на страницу добавления поста.

    /**
     * @Route("/", name="default")
     */
    public function index(PostRepository $postRepository)
    {

        $posts = $this->getDoctrine()->getManager()->getRepository(Post::class)->findBy([], ['postDate' => 'DESC'], 3);

        return $this->render('default/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/{id}", name="post")
     */
    public function post(Post $post)
    {

        return $this->render('default/post.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/add_post", name="add_post")
     */
    public function addPost(Request $request, EntityManagerInterface $entityManager)
    {
        $add_post = new Post(); // новый объект из сущности

        $form = $this->createForm(AddPostType::class, $add_post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($add_post);
            $entityManager->flush();

            return $this->redirectToRoute('add_post');
        }

        return $this->render('default/add_post.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("post/edit/{id}", name="edit_post")
     */
    public function editPost(Request $request, Post $post, EntityManagerInterface $entityManager)
    {
        $editForm = $this->createForm(AddPostType::class, $post);
        $editForm->handleRequest($request);

        if($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post', array('id' => $post->getId()));
        }

        return $this->render('default/edit_post.html.twig', [
            'post' =>$post,
            'editForm' => $editForm->createView(),
        ]);
    }


}