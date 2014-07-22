<?php

namespace Todo\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Todo\Domain\TodoMapper;

class TodoController extends AbstractController
{
    private $twig;
    private $mapper;
    private $router;

    public function __construct(
        \Twig_Environment $twig,
        TodoMapper $mapper,
        UrlGeneratorInterface $router
    )
    {
        $this->twig   = $twig;
        $this->mapper = $mapper;
        $this->router = $router;
    }
    
    public function indexAction()
    {
        return $this->twig->render('index.html.twig', [
            'count' => $this->mapper->countAll(),
            'todos' => $this->mapper->findAll(),
        ]);
    }

    public function todoAction($id)
    {
        if (!$todo = $this->mapper->find($id)) {
            throw $this->createNotFoundException(sprintf('Todo #%u does not exist.', $id));
        }

        return $this->twig->render('todo.html.twig', [ 'todo' => $todo ]);
    }

    public function createAction(Request $request)
    {
        $title = $request->request->get('title');
        if (empty($title)) {
            throw $this->createHttpException(400, 'Missing title to create a new todo.');
        }

        $id = $this->mapper->create($title);

        return $this->redirect($this->router->generate('todo', ['id' => $id]));
    }

    public function closeAction($id)
    {
        if (!$todo = $this->mapper->find($id)) {
            throw $this->createNotFoundException(sprintf('Todo #%u does not exist.', $id));
        }

        if ($todo['is_done']) {
            throw $this->createNotFoundException(sprintf('Todo #%u is already done.', $id));
        }

        $this->mapper->close($id);

        return $this->redirect($this->router->generate('homepage'));
    }

    public function deleteAction($id)
    {
        if (!$todo = $this->mapper->find($id)) {
            throw $this->createNotFoundException(sprintf('Todo #%u does not exist.', $id));
        }

        $this->mapper->delete($id);

        return $this->redirect($this->router->generate('homepage'));
    }
} 
