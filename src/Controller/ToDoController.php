<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ToDoController extends AbstractController
{

    // Return all tasks
    #[Route('/api/tasks', name: 'tasks', methods : ['GET'])]
    public function getAllTasks(TodoRepository $todoRepository, SerializerInterface $serializer): JsonResponse
    {
        $taskList = $todoRepository->findAll();

        $jsonTaskList = $serializer->serialize($taskList, 'json');

        return new JsonResponse(
            $jsonTaskList,
            Response::HTTP_OK, [], true
        );
    }

    //return task id
    #[Route('/api/task/{id}', name: 'task_id', methods : ['GET'])]
    public function getTaskById(TodoRepository $todoRepository, SerializerInterface $serializer, int $id): JsonResponse
    {
        $task = $todoRepository->find($id);

        if ($task){
            $jsonTask = $serializer->serialize($task, 'json');
            return new JsonResponse( $jsonTask , Response::HTTP_OK , [] , true);
        }
        return new JsonResponse(null,Response::HTTP_NOT_FOUND);
    }

    //Delete task
    #[Route('/api/task/{id}', name: 'task_delete', methods : ['DELETE'])]
    public function deleteTask(EntityManagerInterface $entityManager, TodoRepository $todoRepository, int $id): JsonResponse
    {
        $task = $todoRepository->find($id);

        if ($task) {
            $entityManager->remove($task);
            $entityManager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(['message' => 'La tâche n\'a pas été trouvée'], Response::HTTP_NOT_FOUND);
    }

    //Post task
    #[Route('/api/task', name: 'task_add', methods : ['POST'])]
    public function addTask(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        $task = $serializer->deserialize($data, Todo::class, 'json');
        $entityManager->persist($task);
        $entityManager->flush();
        return new JsonResponse(['message' => 'Tâche ajoutée avec succès'], Response::HTTP_CREATED);
    }

}
