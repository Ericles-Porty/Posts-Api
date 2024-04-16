<?php

namespace App\Controllers;

use App\Models\Post;

use App\Services\PostService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostController
{
    // protected $service;

    public function __construct()
    {
        // Preciso descobrir como injetar a dependência do PostService
        // $this->service = new PostService;
    }

    public function index(Request $request, Response $response, $args)
    {
        $service = new PostService;

        $posts = $service->getPosts();

        $response->getBody()->write(json_encode($posts));

        return $response->withStatus(200);
    }

    public function show(Request $request, Response $response, $args)
    {
        $service = new PostService;

        $response->withHeader('Content-Type', 'application/json;charset=utf-8');
        $postId = $args['id'];

        try {
            $post = $service->getPostById($postId);

            if (!$post) {
                $response->getBody()->write(json_encode(['message' => 'Post não encontrado']));

                return $response->withStatus(404);
            }

            $response->getBody()->write(json_encode(
                [
                    'id' => $post->id,
                    'title' => $post->title,
                    'content' => $post->content
                ]
            ));

            return $response->withStatus(200);
        } catch (\Throwable $th) {
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Erro ao buscar post',
                    'error' => $th->getMessage
                ]
            ));

            return $response->withStatus(500);
        }
    }


    public function store(Request $request, Response $response, $args)
    {
        $service = new PostService;
        $data = json_decode($request->getBody()->getContents(), true);

        $post = Post::fromArray($data);

        try {
            $post = $service->createPost($post);

            $response->getBody()->write(json_encode([
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content
            ]));

            return $response->withStatus(201);
        } catch (\Throwable $th) {
            $response
                ->getBody()
                ->write(json_encode([
                    'message' => 'Erro ao criar post',
                    'error' => $th->getMessage,
                ]));

            return $response->withStatus(500);
        }
    }

    public function update(Request $request, Response $response, $args)
    {
        $service = new PostService;

        $postId = $args['id'];

        $data = json_decode($request->getBody()->getContents(), true);

        $post = $service->getPostById($postId);

        if (!$post) {
            $response->getBody()->write(json_encode(['message' => 'Post não encontrado']));

            return $response->withStatus(404);
        }

        $post->title = $data['title'];
        $post->content = $data['content'];

        $updatedPost = $service->updatePost($postId, $post);

        $response->getBody()->write(json_encode([
            'id' => $updatedPost->id,
            'title' => $updatedPost->title,
            'content' => $updatedPost->content
        ]));

        return $response->withStatus(200);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $service = new PostService;

        $postId = $args['id'];

        try {
            $post = $service->deletePost($postId);

            if (!$post) {
                $response->getBody()->write(json_encode(['message' => 'Post não encontrado']));

                return $response->withStatus(404);
            }

            $response->getBody()->write(json_encode(['message' => 'Post deletado com sucesso']));

            return $response->withStatus(200);
        } catch (\Throwable $th) {
            $response->getBody()->write(json_encode(['message' => 'Erro ao deletar post', 'error' => $th->getMessage]));

            return $response->withStatus(500);
        }
    }
}