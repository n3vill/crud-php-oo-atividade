<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Curso;
use App\Repository\CategoriaRepository;
use App\Repository\CursoRepository;

use Dompdf\Dompdf;

use Exception;

class CursoController extends AbstractController
{

    private CursoRepository $repository;

    public function __construct()
    {
        $this->repository = new CursoRepository();
    }

    public function listar(): void
    {
        // $this->checkLogin();
        $rep = new CursoRepository();
        $cursos = $rep->buscarTodos();

        $this->render("curso/listar", [
            'cursos' => $cursos,
        ]);
    }

    public function cadastrar(): void
    {
        
        $rep = new CategoriaRepository();
        if (true === empty($_POST)) {
            $categorias = $rep->buscarTodos();
            $this->render("curso/cadastrar", ['categorias' => $categorias]);
            return;
        }

        $curso = new Curso();
        $curso->nome = $_POST['nome'];
        $curso->descricao = $_POST['descricao'];
        $curso->cargaHoraria = intval($_POST['cargaHoraria']);
        $curso->categoria_id = intval($_POST['categoria']);

        $this->repository->inserir($curso);
        // try {
        // } catch (Exception $exception) {
        //     var_dump($exception->getMessage());
        //     // if (true === str_contains($exception->getMessage(), 'cpf')) {
        //     //     die('CPF ja existe');
        //     // }

        //     // if (true === str_contains($exception->getMessage(), 'email')) {
        //     //     die('Email ja existe');
        //     // }

        //     die('Vish, aconteceu um erro');
        // }

        $this->redirect('/cursos/listar');
    }

    public function excluir(): void
    {
        $id = $_GET['id'];
        $this->repository->excluir($id);
        $this->redirect('/cursos/listar');
    }

    public function editar(): void
    {
        $id = $_GET['id'];
        $rep = new CategoriaRepository();
        $categorias = $rep->buscarTodos();
        $curso = $this->repository->buscarUm($id);
        $this->render("/curso/editar", [
            'categorias' => $categorias,
            'curso' => $curso
        ]);
        if (false === empty($_POST)) {
            $curso = new Curso();
            $curso->nome = $_POST['nome'];
            $curso->descricao = $_POST['descricao'];
            $curso->cargaHoraria = intval($_POST['cargaHoraria']);
            $curso->categoria_id = intval($_POST['categoria']);
            $this->repository->atualizar($curso, $id);
            $this->redirect('/cursos/listar');
        }
    }

    private function renderizar(iterable $cursos)
    {
        $resultado = '';
        foreach ($cursos as $curso){
            $resultado .= "
            <tr>
            <td>{$curso['curso_id']}</td>
            <td>{$curso['curso_nome']}</td>
            <td>{$curso['curso_status']}</td>
            <td>{$curso['curso_descricao']}</td>
            <td>{$curso['curso_carga_horaria']}</td>
            <td>{$curso['categoria_nome']}</td>
            </tr>
            ";
        }
        return $resultado;
    }

    public function gerandoPDF():void
    {
       $dados = $this->repository->buscarTodos();
       $this->relatorio("curso", [
           'cursos' => $dados,
       ]);
    }
}